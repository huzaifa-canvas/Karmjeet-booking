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
              ->filterFormat($request->input('format'));

        // Search by name
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // We can paginate to make it better for the user, or just get all for now
        $classes = $query->orderBy('name')->paginate(12);

        $categories = MartialArtsClass::CATEGORIES;
        $types = MartialArtsClass::TYPES;
        $levels = MartialArtsClass::LEVELS;
        $age_groups = MartialArtsClass::AGE_GROUPS;

        return view('modules.user.schedule-classes.list', compact('classes', 'categories', 'types', 'levels', 'age_groups'));
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
