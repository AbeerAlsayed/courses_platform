<?php
// app/Http/Requests/Course/UpdateCourseRequest.php

namespace App\Http\Requests\Courses;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Handled by Policy
    }

    public function rules(): array
    {
        $courseId = $this->route('course')?->id;

        return [
            'title'         => ['sometimes', 'string', 'max:255'],
            'slug'          => ['sometimes', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/',
                "unique:courses,slug,{$courseId}"],
            'description'   => ['nullable', 'string', 'max:5000'],
            'category_id'   => ['sometimes', 'integer', 'exists:categories,id'],
            'price'         => ['sometimes', 'numeric', 'min:0', 'max:99999.99'],
            'currency'      => ['nullable', 'string', 'size:3'],
            'duration'      => ['nullable', 'integer', 'min:1'],
        ];
    }
}
