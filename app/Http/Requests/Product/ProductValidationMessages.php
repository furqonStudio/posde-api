<?php

namespace App\Http\Requests\Product;

trait ProductValidationMessages
{
    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori produk wajib diisi.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',

            'user_id.required' => 'Pengguna wajib diisi.',
            'user_id.exists' => 'Pengguna yang dipilih tidak valid.',

            'image.image' => 'File yang diunggah harus berupa gambar.',
            'image.mimes' => 'Gambar harus berformat PNG atau JPG.',
            'image.max' => 'Ukuran gambar maksimal adalah 2 MB.',

            'name.required' => 'Nama produk wajib diisi.',
            'name.string' => 'Nama produk harus berupa teks.',
            'name.min' => 'Nama produk minimal harus terdiri dari 3 karakter.',
            'name.max' => 'Nama produk maksimal terdiri dari 255 karakter.',
            'name.unique' => 'Nama produk sudah digunakan.',

            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak ditemukan.',

            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga produk harus berupa angka.',
            'price.min' => 'Harga produk tidak boleh negatif.',

            'stock.required' => 'Stok produk wajib diisi.',
            'stock.integer' => 'Stok produk harus berupa angka bulat.',
            'stock.min' => 'Stok tidak boleh negatif.',
        ];
    }
}
