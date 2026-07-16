<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonRequest;
use App\Models\Course;
use App\Models\Lesson;

class LessonController extends Controller
{
    public function store(LessonRequest $request, Course $course)
    {
        $course->lessons()->create($request->validated());

        return redirect()->route('courses.show', $course)->with('success', 'Lesson added.');
    }

    public function edit(Lesson $lesson)
    {
        $lesson->load('course');

        return view('lessons.edit', compact('lesson'));
    }

    public function update(LessonRequest $request, Lesson $lesson)
    {
        $lesson->update($request->validated());

        return redirect()->route('courses.show', $lesson->course)->with('success', 'Lesson updated.');
    }

    public function destroy(Lesson $lesson)
    {
        $course = $lesson->course;
        $lesson->delete();

        return redirect()->route('courses.show', $course)->with('success', 'Lesson deleted.');
    }
}
