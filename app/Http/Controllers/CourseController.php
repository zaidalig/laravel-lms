<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['category', 'instructor'])->withCount(['lessons', 'enrollments']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->input('search').'%');
        }

        if ($request->filled('category_id')) {
            $query->where('course_category_id', $request->input('category_id'));
        }

        if ($request->filled('level')) {
            $query->where('level', $request->input('level'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $courses = $query->latest()->paginate(10)->withQueryString();
        $categories = CourseCategory::where('status', 'active')->orderBy('name')->get();

        return view('courses.index', compact('courses', 'categories'));
    }

    public function create()
    {
        return view('courses.create', $this->formOptions());
    }

    public function store(CourseRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['title']);

        $course = Course::create($data);

        return redirect()->route('courses.show', $course)
            ->with('success', "Course \"{$course->title}\" created. Add lessons below.");
    }

    public function show(Course $course)
    {
        $course->load(['category', 'instructor', 'lessons', 'quizzes.questions'])->loadCount('enrollments');
        $enrollments = $course->enrollments()->with('user')->latest()->limit(10)->get();

        return view('courses.show', compact('course', 'enrollments'));
    }

    public function progressExport(Course $course)
    {
        $course->loadCount('lessons');
        $enrollments = $course->enrollments()->with('user')->withCount('completions')->orderBy('id')->get();

        return response()->streamDownload(function () use ($course, $enrollments) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Student', 'Email', 'Enrolled At', 'Lessons Completed', 'Total Lessons', 'Progress %', 'Status'], ',', '"', '');

            foreach ($enrollments as $e) {
                $total = $course->lessons_count;
                $percent = $total > 0 ? (int) round($e->completions_count / $total * 100) : 0;

                fputcsv($handle, [
                    $e->user?->name ?? '-',
                    $e->user?->email ?? '-',
                    $e->enrolled_at->format('Y-m-d'),
                    $e->completions_count,
                    $total,
                    $percent,
                    ucfirst($e->status),
                ], ',', '"', '');
            }

            fclose($handle);
        }, "{$course->slug}-progress.csv", ['Content-Type' => 'text/csv']);
    }

    public function edit(Course $course)
    {
        return view('courses.edit', ['course' => $course] + $this->formOptions());
    }

    public function update(CourseRequest $request, Course $course)
    {
        $data = $request->validated();

        if ($data['title'] !== $course->title) {
            $data['slug'] = $this->uniqueSlug($data['title'], $course->id);
        }

        $course->update($data);

        return redirect()->route('courses.show', $course)
            ->with('success', "Course \"{$course->title}\" updated.");
    }

    public function destroy(Course $course)
    {
        $title = $course->title;
        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', "Course \"{$title}\" deleted.");
    }

    private function formOptions(): array
    {
        return [
            'categories' => CourseCategory::where('status', 'active')->orderBy('name')->get(),
            'instructors' => User::whereIn('role', ['owner', 'instructor'])->orderBy('name')->get(),
        ];
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 2;

        while (Course::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
