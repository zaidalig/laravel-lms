<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;

class HomeController extends Controller
{
    public function index()
    {
        $courses = Course::with('category')
            ->withCount('lessons')
            ->where('status', 'published')
            ->latest()
            ->limit(6)
            ->get();

        $categories = CourseCategory::withCount(['courses' => fn ($q) => $q->where('status', 'published')])
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('public.home', compact('courses', 'categories'));
    }
}
