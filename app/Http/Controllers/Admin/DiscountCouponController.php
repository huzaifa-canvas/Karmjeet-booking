<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use Illuminate\Http\Request;

class DiscountCouponController extends Controller
{
    public function index()
    {
        $coupons = DiscountCoupon::latest()->get();
        return view('modules.admin.discount-coupons.list', compact('coupons'));
    }

    public function create()
    {
        return view('modules.admin.discount-coupons.form', ['isEdit' => false, 'coupon' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:discount_coupons,code',
            'name' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
        ]);

        DiscountCoupon::create($validated);
        return redirect()->route('admin.discount-coupons.index')->with(['status' => 'success', 'message' => 'Coupon created successfully.']);
    }

    public function edit($id)
    {
        $coupon = DiscountCoupon::findOrFail($id);
        return view('modules.admin.discount-coupons.form', ['isEdit' => true, 'coupon' => $coupon]);
    }

    public function update(Request $request, $id)
    {
        $coupon = DiscountCoupon::findOrFail($id);
        
        $validated = $request->validate([
            'code' => 'required|string|unique:discount_coupons,code,'.$coupon->id,
            'name' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
        ]);

        $coupon->update($validated);
        return redirect()->route('admin.discount-coupons.index')->with(['status' => 'success', 'message' => 'Coupon updated successfully.']);
    }

    public function destroy($id)
    {
        DiscountCoupon::findOrFail($id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => 'Coupon deleted successfully.']);
    }
}
