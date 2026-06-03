<?php
// app/Http/Requests/Course/CreateCourseRequest.php

namespace App\Http\Requests\Courses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:255'],
            'slug'          => ['required', 'string', 'max:255', 'unique:courses,slug', 'regex:/^[a-z0-9-]+$/'],
            'description'   => ['nullable', 'string', 'max:5000'],
            'category_id'   => ['required', 'integer', 'exists:categories,id'],
            'instructor_id' => ['nullable', 'integer', 'exists:instructors,id'],
            'price'         => ['required', 'numeric', 'min:0', 'max:99999.99'],
            'currency'      => ['nullable', 'string', 'size:3'],
            'duration'      => ['nullable', 'integer', 'min:1'],
        ];
    }
}
