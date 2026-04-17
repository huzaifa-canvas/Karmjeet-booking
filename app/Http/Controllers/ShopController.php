<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingAddress;
use App\Services\Payments\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Shop listing page.
     */
    public function index(Request $request)
    {
        $query = Product::active();

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
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

        $products = $query->paginate(9);
        $categories = Product::active()->select('category')->distinct()->whereNotNull('category')->pluck('category');
        $brands = Product::active()->select('brand')->distinct()->whereNotNull('brand')->pluck('brand');
        $totalProducts = Product::active()->count();

        return view('shop', compact('products', 'categories', 'brands', 'totalProducts'));
    }

    /**
     * Product detail page.
     */
    public function show($slug)
    {
        $product = Product::with('images')->where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::active()
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->limit(5)
            ->get();

        $cart = session()->get('cart', []);
        $inCart = isset($cart[$product->id]);

        return view('shop_details', compact('product', 'relatedProducts', 'inCart'));
    }

    /**
     * AJAX: Add to cart.
     */
    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'name'     => $product->name,
                'price'    => $product->display_price,
                'image'    => $product->image,
                'slug'     => $product->slug,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success'   => true,
            'message'   => 'Product added to cart!',
            'cartCount' => count($cart),
            'cartData'  => $this->getCartArray(),
        ]);
    }

    /**
     * AJAX: Remove from cart.
     */
    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Product removed from cart.',
            'cartCount' => count($cart),
            'cartData'  => $this->getCartArray(),
        ]);
    }

    /**
     * AJAX: Update cart quantity.
     */
    public function updateCartQty(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = max(1, (int) $request->quantity);
            session()->put('cart', $cart);
        }

        return response()->json([
            'success'   => true,
            'cartCount' => count($cart),
            'cartData'  => $this->getCartArray(),
        ]);
    }

    /**
     * AJAX: Get cart data for header dropdown.
     */
    public function getCartData()
    {
        return response()->json([
            'success'   => true,
            'cartCount' => count(session()->get('cart', [])),
            'cartData'  => $this->getCartArray(),
        ]);
    }

    /**
     * Checkout page.
     */
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.index')->with(['status' => 'failed', 'message' => 'Your cart is empty.']);
        }

        $cartTotal = 0;
        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        foreach ($cart as $id => &$item) {
            $cartTotal += $item['price'] * $item['quantity'];
            $item['brand'] = $products[$id]->brand ?? 'Karmjeet';
        }

        return view('checkout', compact('cart', 'cartTotal'));
    }

    /**
     * Place order (COD or Stripe).
     */
    public function placeOrder(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.index')->with(['status' => 'failed', 'message' => 'Your cart is empty.']);
        }

        $request->validate([
            'full_name'      => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'flat_house'     => 'nullable|string|max:255',
            'landmark'       => 'nullable|string|max:255',
            'city'           => 'required|string|max:255',
            'pincode'        => 'nullable|string|max:20',
            'state'          => 'nullable|string|max:255',
            'address_type'   => 'nullable|string|max:50',
            'payment_method' => 'required|in:cod,stripe',
        ]);

        $cartTotal = 0;
        foreach ($cart as $item) {
            $cartTotal += $item['price'] * $item['quantity'];
        }

        // Create Order
        $order = Order::create([
            'user_id'        => Auth::id(),
            'total_amount'   => $cartTotal,
            'status'         => 'pending',
            'payment_method' => $request->payment_method,
        ]);

        // Create Order Items
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $productId,
                'product_name' => $item['name'],
                'quantity'     => $item['quantity'],
                'price'        => $item['price'],
                'total'        => $item['price'] * $item['quantity'],
            ]);

            // Decrease stock
            Product::where('id', $productId)->decrement('stock', $item['quantity']);
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

        // Handle Payment
        if ($request->payment_method === 'stripe') {
            try {
                $session = $this->stripeService->createProductPaymentSession(
                    Auth::user(),
                    $cart,
                    $order->id,
                    route('shop.stripe.success'),
                    route('shop.stripe.cancel')
                );

                $order->update(['stripe_session_id' => $session->id]);

                return redirect($session->url);
            } catch (\Exception $e) {
                return redirect()->back()->with(['status' => 'failed', 'message' => 'Stripe Error: ' . $e->getMessage()]);
            }
        }

        // COD: Clear cart and redirect
        session()->forget('cart');

        return redirect()->route('shop.order.success', $order->id);
    }

    /**
     * Stripe success callback.
     */
    public function stripeSuccess(Request $request)
    {
        $sessionId = $request->get('session_id');
        if (!$sessionId) {
            return redirect()->route('shop.index')->with(['status' => 'failed', 'message' => 'Invalid payment session.']);
        }

        try {
            $stripeSession = $this->stripeService->getCheckoutSession($sessionId);
            $orderId = $stripeSession->metadata->order_id;

            $order = Order::findOrFail($orderId);
            $order->update([
                'status'                => 'processing',
                'payment_status'        => 'paid',
                'stripe_payment_intent' => $stripeSession->payment_intent,
            ]);

            session()->forget('cart');

            return redirect()->route('shop.order.success', $order->id);
        } catch (\Exception $e) {
            return redirect()->route('shop.index')->with(['status' => 'failed', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Pay unpaid Stripe order.
     */
    public function payOrder($id)
    {
        $order = Order::with('items')->where('user_id', Auth::id())->findOrFail($id);

        if ($order->payment_method !== 'stripe' || $order->payment_status === 'paid') {
            return redirect()->back()->with(['status' => 'failed', 'message' => 'Invalid payment request.']);
        }

        // Reconstruct basic cart array from order items format for StripeService
        $cart = [];
        foreach ($order->items as $item) {
            $cart[$item->product_id] = [
                'name' => $item->product_name,
                'price' => $item->price,
                'quantity' => $item->quantity,
            ];
        }

        try {
            $session = $this->stripeService->createProductPaymentSession(
                Auth::user(),
                $cart,
                $order->id,
                route('shop.stripe.success'),
                route('user.orders') // return to orders list on cancel
            );

            $order->update(['stripe_session_id' => $session->id]);

            return redirect($session->url);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'failed', 'message' => 'Stripe Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Stripe cancel callback.
     */
    public function stripeCancel()
    {
        return redirect()->route('shop.checkout')->with(['status' => 'failed', 'message' => 'Payment was cancelled. Your cart is still intact.']);
    }

    /**
     * Order success page.
     */
    public function orderSuccess($id)
    {
        $order = Order::with(['items', 'shippingAddress'])->where('user_id', Auth::id())->findOrFail($id);
        return view('order_success', compact('order'));
    }

    /**
     * User's order history page.
     */
    public function myOrders()
    {
        $orders = Order::with('items')->where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(10);
        return view('my_orders', compact('orders'));
    }

    /**
     * User's order details page.
     */
    public function myOrderDetails($id)
    {
        $order = Order::with(['items', 'shippingAddress'])->where('user_id', Auth::id())->findOrFail($id);
        return view('my_order_details', compact('order'));
    }

    /**
     * Helper: Build cart array for JSON response.
     */
    private function getCartArray()
    {
        $cart = session()->get('cart', []);
        $items = [];
        $total = 0;
        foreach ($cart as $id => $item) {
            $itemTotal = $item['price'] * $item['quantity'];
            $total += $itemTotal;
            $items[] = [
                'id'       => $id,
                'name'     => $item['name'],
                'price'    => $item['price'],
                'image'    => $item['image'] ? asset($item['image']) : null,
                'slug'     => $item['slug'],
                'quantity' => $item['quantity'],
                'total'    => $itemTotal,
            ];
        }
        return ['items' => $items, 'total' => $total];
    }
}
