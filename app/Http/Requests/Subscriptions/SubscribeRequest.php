<?php

namespace App\Http\Requests\Subscriptions;

use Illuminate\Foundation\Http\FormRequest;

class SubscribeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isStudent();
    }

    public function rules(): array
    {
        return [
            'course_id' => 'required|exists:courses,id',
        ];
    }


}
