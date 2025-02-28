<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('category')?->id;

        return [
            'name' => 'required|string|max:255|unique:categories,name,' . ($id ?? 'NULL'),
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'name.required' => 'Nama kategori wajib diisi.',
    //         'name.string' => 'Nama kategori harus berupa teks.',
    //         'name.max' => 'Nama kategori tidak boleh lebih dari 255 karakter.',
    //         'name.unique' => 'Nama kategori sudah digunakan.',
    //     ];
    // }
}
