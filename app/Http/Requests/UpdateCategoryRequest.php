<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $categoryId = $this->route('category');
        if (is_object($categoryId)) {
            $categoryId = $categoryId->category_id;
        }

        return [
            'category' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'category')->ignore($categoryId, 'category_id'),
            ],
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category.required' => 'Nama kategori wajib diisi.',
            'category.unique' => 'Nama kategori sudah digunakan oleh kategori lain.',
            'category.max' => 'Nama kategori maksimal 100 karakter.',
        ];
    }
}

