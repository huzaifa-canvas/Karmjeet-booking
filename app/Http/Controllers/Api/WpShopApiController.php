<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WpShopApiController extends Controller
{
    /**
     * Return public config (Stripe publishable key, etc.)
     * WordPress plugin fetches this instead of storing keys locally.
     */
    public function config()
    {
        return response()->json([
            'success'    => true,
            'stripe_key' => env('STRIPE_KEY'),
            'currency'   => 'usd',
        ]);
    }

    /**
     * List active products with search, filter, sort & pagination.
     */
    public function products(Request $request)
    {
        $query = Product::active();

        // Search
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->category) {
            $query->where('category', $request->category);
        }

        // Brand filter
        if ($request->brand) {
            $query->where('brand', $request->brand);
        }

        // Price range filter
        if ($request->price_range) {
            switch ($request->price_range) {
                case '0-10':
                    $query->where('price', '<=', 10);
                    break;
                case '10-100':
                    $query->whereBetween('price', [10, 100]);
                    break;
                case '100-500':
                    $query->whereBetween('price', [100, 500]);
                    break;
                case '500+':
                    $query->where('price', '>=', 500);
                    break;
            }
        }

        // Sorting
        switch ($request->sort) {
            case 'lowest':
                $query->orderBy('price', 'asc');
                break;
            case 'highest':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('featured', 'desc')->orderBy('created_at', 'desc');
        }

        $products = $query->paginate($request->per_page ?? 12);

        // Transform image URLs to full paths
        $products->getCollection()->transform(function ($product) {
            $product->image_url = $product->image ? asset($product->image) : null;
            $product->display_price = $product->display_price;
            return $product;
        });

        return response()->json([
            'success' => true,
            'data'    => $products,
        ]);
    }

    /**
     * Get a single product detail by slug.
     */
    public function productDetail($slug)
    {
        $product = Product::with('images')->where('slug', $slug)->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
        }

        // Build all image URLs
        $allImages = [];
        if ($product->image) {
            $allImages[] = asset($product->image);
        }
        foreach ($product->images as $img) {
            $allImages[] = asset($img->image);
        }

        $product->image_url  = $product->image ? asset($product->image) : null;
        $product->all_images = $allImages;

        // Related products (same category)
        $related = Product::active()
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->limit(6)
            ->get()
            ->map(function ($p) {
                $p->image_url = $p->image ? asset($p->image) : null;
                return $p;
            });

        return response()->json([
            'success' => true,
            'product' => $product,
            'related' => $related,
        ]);
    }

    /**
     * Get all categories and brands for filter sidebar.
     */
    public function filters()
    {
        $categories = Product::active()
            ->select('category')
            ->distinct()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->pluck('category');

        $brands = Product::active()
            ->select('brand')
            ->distinct()
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->pluck('brand');

        // Price range info
        $priceMin = Product::active()->min('price');
        $priceMax = Product::active()->max('price');

        return response()->json([
            'success'    => true,
            'categories' => $categories,
            'brands'     => $brands,
            'price_min'  => $priceMin,
            'price_max'  => $priceMax,
        ]);
    }

    /**
     * Create a Stripe PaymentIntent for the order total.
     * Returns client_secret for Stripe Elements on-site payment.
     */
    public function createPaymentIntent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Calculate total from actual DB prices (not client-sent prices for security)
        $total = 0;
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product || $product->status !== 'active') {
                return response()->json(['success' => false, 'message' => "Product #{$item['product_id']} is not available."], 400);
            }
            if ($product->stock < $item['quantity']) {
                return response()->json(['success' => false, 'message' => "{$product->name} is out of stock."], 400);
            }
            $price = $product->sale_price ?? $product->price;
            $total += $price * $item['quantity'];
        }

        if ($total <= 0) {
            return response()->json(['success' => false, 'message' => 'Invalid order total.'], 400);
        }

        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

            $paymentIntent = $stripe->paymentIntents->create([
                'amount'   => (int)($total * 100), // cents
                'currency' => 'usd',
                'metadata' => [
                    'source' => 'wordpress_plugin',
                ],
            ]);

            return response()->json([
                'success'       => true,
                'client_secret' => $paymentIntent->client_secret,
                'amount'        => $total,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Payment initialization failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Place a guest order (from WordPress plugin).
     * Supports COD and Stripe (on-site via PaymentIntent).
     */
    public function placeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Guest info
            'guest_name'    => 'required|string|max:255',
            'guest_email'   => 'required|email|max:255',
            'guest_phone'   => 'required|string|max:20',
            // Shipping
            'full_name'     => 'required|string|max:255',
            'phone'         => 'required|string|max:20',
            'flat_house'    => 'nullable|string|max:255',
            'landmark'      => 'nullable|string|max:255',
            'city'          => 'required|string|max:255',
            'pincode'       => 'nullable|string|max:20',
            'state'         => 'nullable|string|max:255',
            'address_type'  => 'nullable|string|max:50',
            // Payment
            'payment_method'     => 'required|in:cod,stripe',
            'stripe_payment_intent' => 'required_if:payment_method,stripe|nullable|string',
            // Cart items
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Calculate total from DB prices (secure)
        $cartTotal = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product || $product->status !== 'active') {
                return response()->json(['success' => false, 'message' => "Product not available."], 400);
            }
            if ($product->stock < $item['quantity']) {
                return response()->json(['success' => false, 'message' => "{$product->name} is out of stock."], 400);
            }

            $price = $product->sale_price ?? $product->price;
            $itemTotal = $price * $item['quantity'];
            $cartTotal += $itemTotal;

            $orderItems[] = [
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'quantity'     => $item['quantity'],
                'price'        => $price,
                'total'        => $itemTotal,
            ];
        }

        // Determine payment status
        $paymentStatus = 'unpaid';
        $stripePaymentIntent = null;

        if ($request->payment_method === 'stripe' && $request->stripe_payment_intent) {
            // Verify PaymentIntent was actually paid
            try {
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                $pi = $stripe->paymentIntents->retrieve($request->stripe_payment_intent);

                if ($pi->status === 'succeeded') {
                    $paymentStatus = 'paid';
                    $stripePaymentIntent = $pi->id;
                } else {
                    return response()->json(['success' => false, 'message' => 'Payment not completed. Status: ' . $pi->status], 400);
                }
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Payment verification failed.'], 400);
            }
        }

        // Create Order (guest — no user_id)
        $order = Order::create([
            'user_id'              => null,
            'guest_name'           => $request->guest_name,
            'guest_email'          => $request->guest_email,
            'guest_phone'          => $request->guest_phone,
            'total_amount'         => $cartTotal,
            'status'               => $request->payment_method === 'stripe' ? 'processing' : 'pending',
            'payment_method'       => $request->payment_method,
            'payment_status'       => $paymentStatus,
            'stripe_payment_intent' => $stripePaymentIntent,
        ]);

        // Create Order Items
        foreach ($orderItems as $oi) {
            OrderItem::create(array_merge($oi, ['order_id' => $order->id]));

            // Decrease stock
            Product::where('id', $oi['product_id'])->decrement('stock', $oi['quantity']);
        }

        // Create Shipping Address
        ShippingAddress::create([
            'order_id'     => $order->id,
            'full_name'    => $request->full_name,
            'phone'        => $request->phone,
            'flat_house'   => $request->flat_house,
            'landmark'     => $request->landmark,
            'city'         => $request->city,
            'pincode'      => $request->pincode,
            'state'        => $request->state,
            'address_type' => $request->address_type ?? 'home',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully!',
            'order'   => [
                'id'             => $order->id,
                'order_number'   => $order->order_number,
                'total_amount'   => $order->total_amount,
                'status'         => $order->status,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'created_at'     => $order->created_at->format('M d, Y h:i A'),
            ],
        ]);
    }
}
