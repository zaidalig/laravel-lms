<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\Quiz;
use App\Models\QuizAttempt;
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

        $enrollment->load(['course.lessons', 'course.quizzes', 'completions', 'quizAttempts']);

        $lessons = $enrollment->course->lessons;
        $quizzes = $enrollment->course->quizzes;
        $completedIds = $enrollment->completions->pluck('lesson_id')->all();
        $attemptsByQuiz = $enrollment->quizAttempts->keyBy('quiz_id');

        $current = null;
        if ($request->filled('lesson')) {
            $current = $lessons->firstWhere('id', (int) $request->input('lesson'));
        }
        $current ??= $lessons->first(fn ($l) => ! in_array($l->id, $completedIds)) ?? $lessons->first();

        return view('my.show', compact('enrollment', 'lessons', 'quizzes', 'completedIds', 'attemptsByQuiz', 'current'));
    }

    public function quiz(Request $request, Enrollment $enrollment, Quiz $quiz)
    {
        abort_unless($enrollment->user_id === $request->user()->id, 403);

        if ($quiz->course_id !== $enrollment->course_id) {
            abort(404);
        }

        $quiz->load('questions');
        $attempt = $enrollment->quizAttempts()->where('quiz_id', $quiz->id)->first();

        return view('my.quiz', compact('enrollment', 'quiz', 'attempt'));
    }

    public function submitQuiz(Request $request, Enrollment $enrollment, Quiz $quiz)
    {
        abort_unless($enrollment->user_id === $request->user()->id, 403);

        if ($quiz->course_id !== $enrollment->course_id) {
            abort(404);
        }

        $quiz->load('questions');

        if ($quiz->questions->isEmpty()) {
            return back()->with('error', 'This quiz has no questions yet.');
        }

        if ($enrollment->quizAttempts()->where('quiz_id', $quiz->id)->exists()) {
            return redirect()->route('my.quizzes.show', ['enrollment' => $enrollment, 'quiz' => $quiz])
                ->with('error', 'You have already submitted this quiz.');
        }

        $answers = $request->input('answers', []);
        $correct = 0;

        foreach ($quiz->questions as $question) {
            $selected = $answers[$question->id] ?? null;

            if ($selected === $question->correct_option) {
                $correct++;
            }
        }

        $score = (int) round($correct / $quiz->questions->count() * 100);
        $passed = $score >= $quiz->requiredPassScore();

        QuizAttempt::create([
            'enrollment_id' => $enrollment->id,
            'quiz_id' => $quiz->id,
            'score' => $score,
            'passed' => $passed,
        ]);

        return redirect()->route('my.quizzes.show', ['enrollment' => $enrollment, 'quiz' => $quiz])
            ->with('success', $passed
                ? "Quiz passed with {$score}%."
                : "Quiz submitted. Score: {$score}% (need {$quiz->requiredPassScore()}% to pass).");
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
