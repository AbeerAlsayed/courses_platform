<?php

namespace App\Http\Requests\Lessons;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // تخصيص حسب الصلاحيات إذا أردت
    }

    public function rules(): array
    {
        return [
            'title'       => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order'       => ['nullable', 'integer'],
            'duration'    => ['nullable', 'integer', 'min:0'],
            'is_free'     => ['nullable', 'boolean'],
            'file'        => ['nullable', 'file', 'mimes:mp4,avi,mov,pdf,doc,docx', 'max:102400'], // 100MB
        ];
    }
}
