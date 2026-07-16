<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonRequest;
use App\Models\Course;
use App\Models\Lesson;
use App\Services\MediaStorage;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function store(LessonRequest $request, Course $course)
    {
        $data = $request->validated();

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = MediaStorage::store($request->file('attachment'), 'lesson-attachments');
        }

        $course->lessons()->create($data);

        return redirect()->route('courses.show', $course)->with('success', 'Lesson added.');
    }

    public function edit(Lesson $lesson)
    {
        $lesson->load('course');

        return view('lessons.edit', compact('lesson'));
    }

    public function update(LessonRequest $request, Lesson $lesson)
    {
        $data = $request->validated();

        if ($request->hasFile('attachment')) {
            if ($lesson->attachment_path) {
                MediaStorage::delete($lesson->attachment_path);
            }
            $data['attachment_path'] = MediaStorage::store($request->file('attachment'), 'lesson-attachments');
        }

        $lesson->update($data);

        return redirect()->route('courses.show', $lesson->course)->with('success', 'Lesson updated.');
    }

    public function destroy(Lesson $lesson)
    {
        $course = $lesson->course;

        if ($lesson->attachment_path) {
            MediaStorage::delete($lesson->attachment_path);
        }

        $lesson->delete();

        return redirect()->route('courses.show', $course)->with('success', 'Lesson deleted.');
    }
}
