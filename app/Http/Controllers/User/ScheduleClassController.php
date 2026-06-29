<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MartialArtsClass;
use Illuminate\Http\Request;

class ScheduleClassController extends Controller
{
    public function index(Request $request)
    {
        $query = MartialArtsClass::where('status', 'active');

        // Apply filters
        $query->filterCategory($request->category)
              ->filterType($request->type)
              ->filterLevel($request->level)
              ->filterAgeGroup($request->age_group)
              ->filterFormat($request->input('format'))
              ->filterRoom($request->room);

        // Search by name
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Just get the classes, no longer grouped by room
        $classes = $query->orderBy('name')->get();

        // Fetch dynamic attributes for filters
        $categories = \App\Models\ClassAttribute::active()->ofType('category')->pluck('name');
        $types = \App\Models\ClassAttribute::active()->ofType('type')->pluck('name');
        $age_groups = \App\Models\ClassAttribute::active()->ofType('age_group')->pluck('name');
        $formats = \App\Models\ClassAttribute::active()->ofType('format')->pluck('name');
        $rooms = \App\Models\ClassAttribute::active()->ofType('room')->pluck('name');
        $levels = MartialArtsClass::LEVELS;

        return view('modules.user.schedule-classes.list', compact('classes', 'categories', 'types', 'levels', 'age_groups', 'formats', 'rooms'));
    }

    public function show($id)
    {
        $class = MartialArtsClass::where('status', 'active')->findOrFail($id);
        
        // Find other related classes (optional, e.g., same category)
        $relatedClasses = MartialArtsClass::where('status', 'active')
            ->where('category', $class->category)
            ->where('id', '!=', $class->id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('modules.user.schedule-classes.detail', compact('class', 'relatedClasses'));
    }
}
