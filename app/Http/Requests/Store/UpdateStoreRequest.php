<?php

namespace App\Http\Requests\Store;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateStoreRequest extends BaseRequest
{
    use StoreValidationMessages;

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'min:3',
                'max:255',
                Rule::unique('stores', 'name')->ignore($this->store),
            ],
            'address' => [
                'sometimes',
                'string',
                'max:500',
            ],
            'business_type' => [
                'sometimes',
                'string',
                'max:100',
            ],
        ];
    }
}
