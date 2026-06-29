<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassAttribute;
use Illuminate\Http\Request;

class ClassAttributeController extends Controller
{
    public function index(Request $request)
    {
        $query = ClassAttribute::query();

        if ($request->type && in_array($request->type, ['category', 'type', 'age_group', 'format', 'room'])) {
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
        $counts = [
            'category'  => ClassAttribute::where('type', 'category')->count(),
            'type'      => ClassAttribute::where('type', 'type')->count(),
            'age_group' => ClassAttribute::where('type', 'age_group')->count(),
            'format'    => ClassAttribute::where('type', 'format')->count(),
            'room'      => ClassAttribute::where('type', 'room')->count(),
        ];

        return view('modules.admin.class-attributes.list', compact('attributes', 'counts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'   => 'required|in:category,type,age_group,format,room',
            'name'   => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        ClassAttribute::create($request->only('type', 'name', 'status'));

        $labels = ['category' => 'Category', 'type' => 'Type', 'age_group' => 'Age Group', 'format' => 'Format', 'room' => 'Room / Gym Area'];

        return redirect()->back()
            ->with(['status' => 'success', 'message' => ($labels[$request->type] ?? ucfirst($request->type)) . ' added successfully.']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $attribute = ClassAttribute::findOrFail($id);
        $attribute->update($request->only('name', 'status'));

        return redirect()->back()
            ->with(['status' => 'success', 'message' => $attribute->type_label . ' updated successfully.']);
    }

    public function destroy($id)
    {
        $attribute = ClassAttribute::findOrFail($id);
        $label = $attribute->type_label;
        $attribute->delete();

        return redirect()->back()
            ->with(['status' => 'success', 'message' => $label . ' deleted successfully.']);
    }
}
