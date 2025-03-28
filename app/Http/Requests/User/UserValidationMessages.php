<?php

namespace App\Http\Requests\User;

trait UserValidationMessages
{
    public function messages(): array
    {
        return [
            'name.required' => 'Nama pengguna wajib diisi.',
            'name.string' => 'Nama pengguna harus berupa teks.',
            'name.min' => 'Nama pengguna minimal 3 karakter.',
            'name.max' => 'Nama pengguna maksimal 255 karakter.',
            'name.unique' => 'Nama pengguna sudah terdaftar.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus berupa alamat email yang valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.max' => 'Password maksimal 255 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            // 'phone.required' => 'Nomor telepon wajib diisi.',
            // 'phone.string' => 'Nomor telepon harus berupa teks.',
            // 'phone.max' => 'Nomor telepon maksimal 15 karakter.',
            // 'phone.unique' => 'Nomor telepon sudah terdaftar.',
        ];
    }
}
