<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use Illuminate\Http\Request;

class MyLearningController extends Controller
{
    public function index(Request $request)
    {
        $enrollments = $request->user()->enrollments()
            ->with(['course.category', 'completions'])
            ->latest()
            ->get();

        return view('my.index', compact('enrollments'));
    }

    public function show(Request $request, Enrollment $enrollment)
    {
        abort_unless($enrollment->user_id === $request->user()->id, 403);

        $enrollment->load(['course.lessons', 'completions']);

        $lessons = $enrollment->course->lessons;
        $completedIds = $enrollment->completions->pluck('lesson_id')->all();

        $current = null;
        if ($request->filled('lesson')) {
            $current = $lessons->firstWhere('id', (int) $request->input('lesson'));
        }
        $current ??= $lessons->first(fn ($l) => ! in_array($l->id, $completedIds)) ?? $lessons->first();

        return view('my.show', compact('enrollment', 'lessons', 'completedIds', 'current'));
    }

    public function complete(Request $request, Enrollment $enrollment, Lesson $lesson)
    {
        abort_unless($enrollment->user_id === $request->user()->id, 403);

        if ($lesson->course_id !== $enrollment->course_id) {
            return back()->with('error', 'That lesson does not belong to this course.');
        }

        LessonCompletion::firstOrCreate(
            ['enrollment_id' => $enrollment->id, 'lesson_id' => $lesson->id],
            ['completed_at' => now()],
        );

        if ($enrollment->progressPercent() >= 100 && $enrollment->status !== 'completed') {
            $enrollment->update(['status' => 'completed']);

            return redirect()->route('my.courses.show', $enrollment)
                ->with('success', 'Course completed. Congratulations!');
        }

        return redirect()->route('my.courses.show', ['enrollment' => $enrollment, 'lesson' => $lesson->id])
            ->with('success', "Lesson \"{$lesson->title}\" marked complete.");
    }
}
