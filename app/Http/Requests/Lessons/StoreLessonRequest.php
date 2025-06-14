<?php

namespace App\Http\Requests\Lessons;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // يمكنك تخصيصها لاحقًا بصلاحيات المستخدم
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order'       => ['nullable', 'integer'],
            'duration'    => ['nullable', 'integer', 'min:0'],
            'is_free'     => ['nullable', 'boolean'],
            'file'        => ['required', 'file', 'mimes:mp4,avi,mov,pdf,doc,docx', 'max:102400'], // 100MB
        ];
    }
}
