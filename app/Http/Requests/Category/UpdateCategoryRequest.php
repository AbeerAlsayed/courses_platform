<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', Rule::unique('categories')->ignore($this->route('id')),],
            'slug' => ['required', 'string', Rule::unique('categories')->ignore($this->route('id')),],
            'description' => 'nullable|string',
        ];
    }
}
