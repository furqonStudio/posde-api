<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;
use App\Http\Requests\Product\ProductValidationMessages;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends BaseRequest
{
    use ProductValidationMessages;

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'min:3',
                'max:255',
                Rule::unique('products', 'name')->ignore($this->product),
            ],
            'category_id' => [
                'sometimes',
                Rule::exists('categories', 'id'),
            ],
            'price' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
            'stock' => [
                'sometimes',
                'integer',
                'min:0',
            ],
        ];
    }
}
