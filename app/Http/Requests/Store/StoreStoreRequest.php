<?php

namespace App\Http\Requests\Store;

use App\Enums\BusinessType;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

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
            'address' => [
                'required',
                'string',
                'max:500',
            ],
            'business_type' => ['required', new Enum(BusinessType::class)],
        ];
    }
}
