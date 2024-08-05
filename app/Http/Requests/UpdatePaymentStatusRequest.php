<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @property string $id
 * @property string $status
 */
class UpdatePaymentStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|string|exists:payments,id',
            'status' => 'required|string|in:CREATED,PAID',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'Пользователь не найден.',
            'status.in' => 'Передан некорректный статус',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
