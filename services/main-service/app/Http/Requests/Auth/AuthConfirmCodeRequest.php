<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthConfirmCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|size:5'
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Код подтверждения обязателен к заполнению.',
            'code.string' => 'Код подтверждения должен быть строкой.',
            'code.size' => 'Код подтверждения должен состоять из 5 символов.'
        ];
    }
}
