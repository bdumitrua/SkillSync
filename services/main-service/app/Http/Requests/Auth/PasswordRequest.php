<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'password' => 'required|string|min:8',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Пароль обязателен к заполнению.',
            'password.string' => 'Пароль должен быть строкой.',
            'password.min' => 'Пароль должен быть не менее 8 символов.',
        ];
    }
}
