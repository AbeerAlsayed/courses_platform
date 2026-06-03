<?php
// app/Http/Requests/Course/ChangeCourseStatusRequest.php

namespace App\Http\Requests\Courses;

use App\Domains\Courses\Enums\CourseStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeCourseStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Handled by Policy
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in([
                CourseStatus::Approved->value,
                CourseStatus::Rejected->value,
            ])],
        ];
    }
}
