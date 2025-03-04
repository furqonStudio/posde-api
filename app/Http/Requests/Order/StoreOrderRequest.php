<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'items' => [
                'required',
                'array',
            ],
            'items.*.product_id' => [
                'required',
                Rule::exists('products', 'id'),
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Pesanan harus memiliki setidaknya satu item.',
            'items.*.product_id.required' => 'Produk wajib diisi.',
            'items.*.product_id.exists' => 'Produk tidak ditemukan.',
            'items.*.quantity.required' => 'Jumlah produk wajib diisi.',
            'items.*.quantity.integer' => 'Jumlah harus berupa angka.',
            'items.*.quantity.min' => 'Minimal jumlah produk adalah 1.',
        ];
    }
}
