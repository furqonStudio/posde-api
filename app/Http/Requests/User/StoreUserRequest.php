<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends BaseRequest
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
                Rule::unique('users', 'name')->ignore($this->user),
            ],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user),
            ],
            'password' => [
                'sometimes',
                'string',
                'min:6',
                'max:255',
            ],
        ];
    }
}
