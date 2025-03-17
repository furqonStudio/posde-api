<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', Rule::exists('products', 'id')],
            'items.*.quantity' => ['required', 'integer', 'min:1'],

            'payment' => ['required', 'array'],
            'payment.method' => ['required', Rule::in(['cash', 'cashless'])],
            'payment.amount' => ['required', 'numeric', 'min:0'],
            'payment.paid_amount' => [
                'nullable',
                'numeric',
                'min:0',
                Rule::requiredIf(fn() => $this->input('payment.method') === 'cash'),
            ],
            'payment.change' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Pesanan harus memiliki setidaknya satu item.',
            'items.min' => 'Pesanan harus memiliki setidaknya satu item.',
            'items.*.product_id.required' => 'Produk wajib diisi.',
            'items.*.product_id.exists' => 'Produk tidak ditemukan.',
            'items.*.quantity.required' => 'Jumlah produk wajib diisi.',
            'items.*.quantity.integer' => 'Jumlah harus berupa angka.',
            'items.*.quantity.min' => 'Minimal jumlah produk adalah 1.',

            'payment.required' => 'Data pembayaran wajib diisi.',
            'payment.array' => 'Format pembayaran tidak valid.',
            'payment.method.required' => 'Metode pembayaran wajib diisi.',
            'payment.method.in' => 'Metode pembayaran harus cash atau cashless.',
            'payment.amount.required' => 'Jumlah pembayaran wajib diisi.',
            'payment.amount.numeric' => 'Jumlah pembayaran harus berupa angka.',
            'payment.amount.min' => 'Jumlah pembayaran tidak boleh negatif.',

            'payment.paid_amount.required' => 'Jumlah yang dibayar wajib diisi untuk pembayaran cash.',
            'payment.paid_amount.numeric' => 'Jumlah yang dibayar harus berupa angka.',
            'payment.paid_amount.min' => 'Jumlah yang dibayar tidak boleh negatif.',

            'payment.change.numeric' => 'Kembalian harus berupa angka.',
            'payment.change.min' => 'Kembalian tidak boleh negatif.',
        ];
    }
}
