<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Почта является обязательным полем.',
            'email.email'    => 'Введена некорректная почта.',
            'email.max'    => 'Длина почты может быть не более 255 символов.',

            'password.required' => 'Пароль является обязательным полем',
            'password.min' => 'Длина пароля должна быть 8 и более символов',
        ];
    }
}
