<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
        $productId = $this->route('product');
        if (is_object($productId)) {
            $productId = $productId->product_id;
        }

        return [
            'name' => 'sometimes|required|string|max:150',
            'description' => 'nullable|string',
            'category_id' => 'sometimes|required|integer|exists:categories,category_id',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($productId, 'product_id'),
            ],
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
            'name.required' => 'Nama produk wajib diisi jika disertakan.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid di database.',
            'price.numeric' => 'Harga produk harus berupa angka numerik.',
            'stock.integer' => 'Stok produk harus berupa bilangan bulat.',
            'slug.unique' => 'Slug produk sudah digunakan oleh produk lain.',
            'img.image' => 'File gambar produk harus berupa file gambar yang valid.',
            'img.mimes' => 'Format gambar yang diperbolehkan hanya JPG, JPEG, atau PNG.',
            'img.max' => 'Ukuran file gambar maksimal adalah 2MB (2048 KB).',
        ];
    }
}
