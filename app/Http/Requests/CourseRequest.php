<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canManageCourses() ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'course_category_id' => 'nullable|exists:course_categories,id',
            'instructor_id' => 'nullable|exists:users,id',
            'description' => 'required|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'price' => 'required|numeric|min:0|max:999999',
            'duration_hours' => 'required|integer|min:1|max:1000',
            'status' => 'required|in:draft,published,archived',
        ];
    }
}
