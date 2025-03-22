<?php

namespace App\Http\Requests\Store;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class StoreStoreRequest extends BaseRequest
{

    use StoreValidationMessages;

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('stores', 'name')->ignore($this->store),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('stores', 'email')->ignore($this->store),
            ],
            'address' => [
                'required',
                'string',
                'max:500',
            ],
            'phone' => [
                'required',
                'string',
                'max:15',
                Rule::unique('stores', 'phone')->ignore($this->store),
            ],
            'business_type' => [
                'required',
                'string',
                'max:100',
            ],
        ];
    }
}
