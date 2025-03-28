<?php

namespace App\Http\Requests\Store;

trait StoreValidationMessages
{
    public function messages(): array
    {
        return [
            'name.required' => 'Nama toko wajib diisi.',
            'name.string' => 'Nama toko harus berupa teks.',
            'name.min' => 'Nama toko minimal 3 karakter.',
            'name.max' => 'Nama toko maksimal 255 karakter.',
            'name.unique' => 'Nama toko sudah digunakan.',

            'address.required' => 'Alamat toko wajib diisi.',
            'address.string' => 'Alamat toko harus berupa teks.',
            'address.max' => 'Alamat toko maksimal 500 karakter.',

            'business_type.required' => 'Jenis usaha wajib diisi.',
            'business_type.string' => 'Jenis usaha harus berupa teks.',
            'business_type.max' => 'Jenis usaha maksimal 100 karakter.',
        ];
    }
}
