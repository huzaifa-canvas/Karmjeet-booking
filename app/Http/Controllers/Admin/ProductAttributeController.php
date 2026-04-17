<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductAttribute::query();

        if ($request->type && in_array($request->type, ['category', 'brand'])) {
            $query->where('type', $request->type);
        }
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $attributes = $query->orderBy('type')->orderBy('name')->get();

        // Counts for badges
        $totalCategories = ProductAttribute::categories()->count();
        $totalBrands = ProductAttribute::brands()->count();

        return view('modules.admin.product-attributes.list', compact('attributes', 'totalCategories', 'totalBrands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'   => 'required|in:category,brand',
            'name'   => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        ProductAttribute::create($request->only('type', 'name', 'status'));

        return redirect()->back()
            ->with(['status' => 'success', 'message' => ucfirst($request->type) . ' added successfully.']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $attribute = ProductAttribute::findOrFail($id);
        $attribute->update($request->only('name', 'status'));

        return redirect()->back()
            ->with(['status' => 'success', 'message' => ucfirst($attribute->type) . ' updated successfully.']);
    }

    public function destroy($id)
    {
        $attribute = ProductAttribute::findOrFail($id);
        $type = $attribute->type;
        $attribute->delete();

        return redirect()->back()
            ->with(['status' => 'success', 'message' => ucfirst($type) . ' deleted successfully.']);
    }
}
