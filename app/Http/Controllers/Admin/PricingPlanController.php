<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use App\Models\MartialArtsClass;
use Illuminate\Http\Request;

class PricingPlanController extends Controller
{
    public function index(Request $request)
    {
        $query = PricingPlan::with('martialArtsClass');

        if ($request->class_id) {
            if ($request->class_id == 'global') {
                $query->whereNull('martial_arts_class_id');
            } else {
                $query->where('martial_arts_class_id', $request->class_id);
            }
        }

        if ($request->interval) {
            $query->where('interval', $request->interval);
        }

        $plans = $query->orderBy('martial_arts_class_id')->orderBy('price')->get();
        $classes = MartialArtsClass::orderBy('name')->get();

        return view('modules.admin.pricing-plans.index', compact('plans', 'classes'));
    }

    public function create()
    {
        $classes = MartialArtsClass::orderBy('name')->get();
        $isEdit = false;
        $plan = null;
        return view('modules.admin.pricing-plans.form', compact('classes', 'isEdit', 'plan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'martial_arts_class_id' => 'nullable|exists:martial_arts_classes,id',
            'name'                  => 'required|string|max:255',
            'price'                 => 'required|numeric|min:0',
            'interval'              => 'required|in:monthly,weekly,one-time',
            'class_limit_per_week'  => 'nullable|integer|min:1',
            'is_tax_inclusive'      => 'nullable|boolean',
            'is_active'             => 'nullable|boolean',
            'description'           => 'nullable|string',
        ]);

        $validated['is_tax_inclusive'] = $request->has('is_tax_inclusive');
        $validated['is_active'] = $request->has('is_active');

        PricingPlan::create($validated);

        return redirect()->route('admin.pricing-plans.index')
            ->with(['status' => 'success', 'message' => 'Pricing plan created successfully.']);
    }

    public function edit($id)
    {
        $plan = PricingPlan::findOrFail($id);
        $classes = MartialArtsClass::orderBy('name')->get();
        $isEdit = true;
        return view('modules.admin.pricing-plans.form', compact('classes', 'isEdit', 'plan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'martial_arts_class_id' => 'nullable|exists:martial_arts_classes,id',
            'name'                  => 'required|string|max:255',
            'price'                 => 'required|numeric|min:0',
            'interval'              => 'required|in:monthly,weekly,one-time',
            'class_limit_per_week'  => 'nullable|integer|min:1',
            'is_tax_inclusive'      => 'nullable|boolean',
            'is_active'             => 'nullable|boolean',
            'description'           => 'nullable|string',
        ]);

        $validated['is_tax_inclusive'] = $request->has('is_tax_inclusive');
        $validated['is_active'] = $request->has('is_active');

        PricingPlan::findOrFail($id)->update($validated);

        return redirect()->route('admin.pricing-plans.index')
            ->with(['status' => 'success', 'message' => 'Pricing plan updated successfully.']);
    }

    public function destroy($id)
    {
        PricingPlan::findOrFail($id)->delete();
        return redirect()->route('admin.pricing-plans.index')
            ->with(['status' => 'success', 'message' => 'Pricing plan deleted successfully.']);
    }
}
