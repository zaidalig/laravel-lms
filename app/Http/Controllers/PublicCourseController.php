<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class PublicCourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with('category')
            ->withCount('lessons')
            ->where('status', 'published');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->input('search').'%');
        }

        if ($request->filled('category_id')) {
            $query->where('course_category_id', $request->input('category_id'));
        }

        if ($request->filled('level')) {
            $query->where('level', $request->input('level'));
        }

        $courses = $query->latest()->paginate(9)->withQueryString();
        $categories = CourseCategory::where('status', 'active')->orderBy('name')->get();

        return view('public.courses', compact('courses', 'categories'));
    }

    public function show(Course $course)
    {
        abort_unless($course->isPublished(), 404);

        $course->load(['category', 'instructor', 'lessons'])->loadCount('enrollments');

        $enrollment = auth()->check()
            ? Enrollment::where('course_id', $course->id)->where('user_id', auth()->id())->first()
            : null;

        return view('public.course', compact('course', 'enrollment'));
    }

    public function enroll(Request $request, Course $course)
    {
        abort_unless($course->isPublished(), 404);

        $exists = Enrollment::where('course_id', $course->id)
            ->where('user_id', $request->user()->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'You are already enrolled in this course.');
        }

        Enrollment::create([
            'course_id' => $course->id,
            'user_id' => $request->user()->id,
            'enrolled_at' => today(),
            'status' => 'active',
        ]);

        return redirect()->route('my.courses')
            ->with('success', "Enrolled in \"{$course->title}\". Happy learning!");
    }
}
