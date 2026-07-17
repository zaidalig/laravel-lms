<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizQuestionRequest;
use App\Models\Quiz;
use App\Models\QuizQuestion;

class QuizQuestionController extends Controller
{
    public function store(QuizQuestionRequest $request, Quiz $quiz)
    {
        $quiz->questions()->create($request->validated());

        return redirect()->route('quizzes.edit', $quiz)->with('success', 'Question added.');
    }

    public function destroy(QuizQuestion $question)
    {
        $quiz = $question->quiz;
        $question->delete();

        return redirect()->route('quizzes.edit', $quiz)->with('success', 'Question deleted.');
    }
}
