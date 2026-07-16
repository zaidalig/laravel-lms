<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canManageCourses() ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'pass_score' => 'nullable|integer|min:0|max:100',
        ];
    }
}
