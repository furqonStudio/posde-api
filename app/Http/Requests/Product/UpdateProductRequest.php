<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;
use App\Http\Requests\Product\ProductValidationMessages;

class UpdateProductRequest extends BaseRequest
{
    use ProductValidationMessages;

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|unique:products,name,' . $this->product,
            'category_id' => 'sometimes|exists:categories,id',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
        ];
    }
}
