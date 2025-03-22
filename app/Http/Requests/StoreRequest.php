<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class StoreRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('stores', 'name')->ignore($this->store),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('stores', 'email')->ignore($this->store),
            ],
            'address' => [
                'required',
                'string',
                'max:500',
            ],
            'phone' => [
                'required',
                'string',
                'max:15',
                Rule::unique('stores', 'phone')->ignore($this->store),
            ],
            'business_type' => [
                'required',
                'string',
                'max:100',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama toko wajib diisi.',
            'name.string' => 'Nama toko harus berupa teks.',
            'name.min' => 'Nama toko minimal 3 karakter.',
            'name.max' => 'Nama toko maksimal 255 karakter.',
            'name.unique' => 'Nama toko sudah digunakan.',

            'email.required' => 'Email toko wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email toko maksimal 255 karakter.',
            'email.unique' => 'Email toko sudah digunakan.',

            'address.required' => 'Alamat toko wajib diisi.',
            'address.string' => 'Alamat toko harus berupa teks.',
            'address.max' => 'Alamat toko maksimal 500 karakter.',

            'phone.required' => 'Nomor telepon toko wajib diisi.',
            'phone.string' => 'Nomor telepon toko harus berupa teks.',
            'phone.max' => 'Nomor telepon toko maksimal 15 karakter.',
            'phone.unique' => 'Nomor telepon toko sudah digunakan.',

            'business_type.required' => 'Jenis usaha wajib diisi.',
            'business_type.string' => 'Jenis usaha harus berupa teks.',
            'business_type.max' => 'Jenis usaha maksimal 100 karakter.',
        ];
    }
}
