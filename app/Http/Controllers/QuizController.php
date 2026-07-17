<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizRequest;
use App\Models\Course;
use App\Models\Quiz;

class QuizController extends Controller
{
    public function store(QuizRequest $request, Course $course)
    {
        $course->quizzes()->create($request->validated());

        return redirect()->route('courses.show', $course)->with('success', 'Quiz added.');
    }

    public function edit(Quiz $quiz)
    {
        $quiz->load(['course', 'questions']);

        return view('quizzes.edit', compact('quiz'));
    }

    public function update(QuizRequest $request, Quiz $quiz)
    {
        $quiz->update($request->validated());

        return redirect()->route('quizzes.edit', $quiz)->with('success', 'Quiz updated.');
    }

    public function destroy(Quiz $quiz)
    {
        $course = $quiz->course;
        $quiz->delete();

        return redirect()->route('courses.show', $course)->with('success', 'Quiz deleted.');
    }
}
