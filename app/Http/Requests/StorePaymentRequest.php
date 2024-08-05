<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|string|unique:payments',
            'login' => 'required|string|exists:users,login',
            'details' => 'required|string',
            'amount' => 'required|numeric',
            'currency' => 'required|string',
            'status' => 'required|in:CREATED,PAID',
        ];
    }

    public function messages(): array
    {
        return [
            'login.exists' => 'Пользователь не найден.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
