<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\BaseRequest;

class UpdateOrderRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status harus diisi.',
            'status.string' => 'Status harus berupa teks.',
        ];
    }
}
