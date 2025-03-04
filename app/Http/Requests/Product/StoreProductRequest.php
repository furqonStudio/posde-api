<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;
use App\Http\Requests\Product\ProductValidationMessages;
use Illuminate\Validation\Rule;

class StoreProductRequest extends BaseRequest
{
    use ProductValidationMessages;

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('products', 'name')->ignore($this->product),
            ],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id'),
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
            ],
        ];
    }
}
