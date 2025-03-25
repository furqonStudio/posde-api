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
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('users', 'name')->ignore($this->user),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user),
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:255',
                'confirmed'
            ],
        ];
    }
}
