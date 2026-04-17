<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('images');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->category) {
            $query->where('category', $request->category);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = Product::select('category')->distinct()->whereNotNull('category')->pluck('category');

        return view('modules.admin.products.list', compact('products', 'categories'));
    }

    public function create()
    {
        $isEdit = false;
        $product = null;
        return view('modules.admin.products.form', compact('isEdit', 'product'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category'    => 'nullable|string|max:255',
            'brand'       => 'nullable|string|max:255',
            'stock'       => 'required|integer|min:0',
            'status'      => 'required|in:active,inactive',
            'featured'    => 'nullable',
            'extra_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            $validated['featured'] = $request->has('featured') ? 1 : 0;
            $validated['slug'] = Str::slug($request->name);

            // Ensure unique slug
            $count = Product::where('slug', 'like', $validated['slug'] . '%')->count();
            if ($count > 0) {
                $validated['slug'] .= '-' . ($count + 1);
            }

            // Handle main image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('assets/images/products'), $filename);
                $validated['image'] = 'assets/images/products/' . $filename;
            }

            unset($validated['extra_images']);
            $product = Product::create($validated);

            // Handle extra images
            if ($request->hasFile('extra_images')) {
                foreach ($request->file('extra_images') as $index => $file) {
                    $filename = time() . '_' . $index . '_' . $file->getClientOriginalName();
                    $file->move(public_path('assets/images/products'), $filename);
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image'      => 'assets/images/products/' . $filename,
                        'sort_order' => $index,
                    ]);
                }
            }

            return redirect()->route('admin.products.index')
                ->with(['status' => 'success', 'message' => 'Product created successfully.']);

        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with(['status' => 'failed', 'message' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $isEdit = true;
        $product = Product::with('images')->findOrFail($id);
        return view('modules.admin.products.form', compact('isEdit', 'product'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category'    => 'nullable|string|max:255',
            'brand'       => 'nullable|string|max:255',
            'stock'       => 'required|integer|min:0',
            'status'      => 'required|in:active,inactive',
            'featured'    => 'nullable',
            'extra_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            $product = Product::findOrFail($id);
            $validated['featured'] = $request->has('featured') ? 1 : 0;

            // Handle main image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('assets/images/products'), $filename);
                $validated['image'] = 'assets/images/products/' . $filename;
            } else {
                unset($validated['image']);
            }

            unset($validated['extra_images']);
            $product->update($validated);

            // Handle extra images
            if ($request->hasFile('extra_images')) {
                $maxSort = $product->images()->max('sort_order') ?? 0;
                foreach ($request->file('extra_images') as $index => $file) {
                    $filename = time() . '_' . $index . '_' . $file->getClientOriginalName();
                    $file->move(public_path('assets/images/products'), $filename);
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image'      => 'assets/images/products/' . $filename,
                        'sort_order' => $maxSort + $index + 1,
                    ]);
                }
            }

            return redirect()->route('admin.products.edit', $id)
                ->with(['status' => 'success', 'message' => 'Product updated successfully.']);

        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with(['status' => 'failed', 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        Product::where('id', $id)->delete();
        return redirect()->back()
            ->with(['status' => 'success', 'message' => 'Product deleted successfully.']);
    }

    /**
     * Delete a single product image (AJAX).
     */
    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);
        // Delete physical file
        $path = public_path($image->image);
        if (file_exists($path)) {
            unlink($path);
        }
        $image->delete();

        return response()->json(['success' => true, 'message' => 'Image deleted.']);
    }
}
