<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MartialArtsClass;
use Illuminate\Http\Request;

class ScheduleSessionController extends Controller
{
    public function index(Request $request)
    {
        $query = MartialArtsClass::query();

        // Apply filters
        $query->filterCategory($request->category)
              ->filterType($request->type)
              ->filterLevel($request->level)
              ->filterAgeGroup($request->age_group)
              ->filterFormat($request->input('format'));

        // Search by name
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $classes = $query->orderBy('category')->orderBy('name')->get();

        // Group by category for display
        $groupedClasses = $classes->groupBy('category');

        return view('modules.admin.schedule-sessions.list', compact('classes', 'groupedClasses'));
    }

    public function create()
    {
        $isEdit = false;
        $class = null;
        return view('modules.admin.schedule-sessions.form', compact('isEdit', 'class'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'category'    => 'required|string',
            'type'        => 'required|string',
            'level'       => 'required|string',
            'age_group'   => 'nullable|string',
            'format'      => 'nullable|string',
            'instructor'  => 'nullable|string|max:255',
            'price'       => 'nullable|numeric|min:0',
            'status'      => 'required|in:active,inactive',
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('assets/images/classes'), $filename);
                $validated['image'] = 'assets/images/classes/' . $filename;
            }

            MartialArtsClass::create($validated);
            return redirect()->route('schedule-session-list')
                ->with(['status' => 'success', 'message' => 'Class created successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with(['status' => 'failed', 'message' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $isEdit = true;
        $class = MartialArtsClass::findOrFail($id);
        return view('modules.admin.schedule-sessions.form', compact('isEdit', 'class'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'category'    => 'required|string',
            'type'        => 'required|string',
            'level'       => 'required|string',
            'age_group'   => 'nullable|string',
            'format'      => 'nullable|string',
            'instructor'  => 'nullable|string|max:255',
            'price'       => 'nullable|numeric|min:0',
            'status'      => 'required|in:active,inactive',
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('assets/images/classes'), $filename);
                $validated['image'] = 'assets/images/classes/' . $filename;
            } else {
                unset($validated['image']);
            }

            MartialArtsClass::where('id', $id)->update($validated);
            return redirect()->route('schedule-session-edit', $id)
                ->with(['status' => 'success', 'message' => 'Class updated successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with(['status' => 'failed', 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        MartialArtsClass::where('id', $id)->delete();
        return redirect()->back()
            ->with(['status' => 'success', 'message' => 'Class deleted successfully.']);
    }
}
