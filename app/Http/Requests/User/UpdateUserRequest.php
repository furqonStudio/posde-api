<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends BaseRequest
{
    use UserValidationMessages;

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
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('stores', 'email')->ignore($this->store),
            ],
            'password' => [
                'sometimes',
                'string',
                'min:6',
                'max:255',
            ],
            'store_id' => ['nullable', 'exists:stores,id'],
        ];
    }
}
