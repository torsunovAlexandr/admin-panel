<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @property string $login
 * @property string $password
 */
class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'login' => 'required|string|exists:users,login',
            'password' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'login.exists' => 'Пользователь не найден.',
            'login.required' => 'Введите логин.',
            'password.required' => 'Введите пароль.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}

