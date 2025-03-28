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
            'category_id' => [
                'sometimes',
                Rule::exists('categories', 'id'),
            ],
            'user_id' => [
                'sometimes',
                Rule::exists('users', 'id'),
            ],
            'image' => [
                'sometimes',
                'nullable',
                'image',
                'mimes:png,jpg',
                'max:2048'
            ],
            'name' => [
                'sometimes',
                'string',
                'min:3',
                'max:255',
                Rule::unique('products', 'name')->ignore($this->product),
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
