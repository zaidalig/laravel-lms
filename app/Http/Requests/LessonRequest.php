<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canManageCourses() ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'video_url' => 'nullable|url|max:255',
            'attachment' => 'nullable|file|mimes:pdf|max:10240',
            'position' => 'required|integer|min:1|max:1000',
            'duration_minutes' => 'required|integer|min:1|max:1000',
        ];
    }
}
