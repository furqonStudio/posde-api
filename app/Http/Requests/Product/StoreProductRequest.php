<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;
use App\Http\Requests\Product\ProductValidationMessages;

class StoreProductRequest extends BaseRequest
{
    use ProductValidationMessages;

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:products,name,' . $this->product,
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ];
    }
}
