<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|max:255|unique:users',
            'birthdate' => 'required|date|date_format:Y-m-d',
        ];
    }

    public function messages()
    {
        return [
            'firstName.required' => 'Имя является обязательным полем.',
            'firstName.string'   => 'Имя должно быть строкой.',
            'firstName.max'      => 'Имя может быть не длиннее 255 символов.',

            'lastName.required' => 'Имя является обязательным полем.',
            'lastName.string'   => 'Имя должно быть строкой.',
            'lastName.max'      => 'Имя может быть не длиннее 255 символов.',

            'password.required' => 'Пароль обязателен к заполнению.',
            'password.string' => 'Пароль должен быть строкой.',
            'password.min' => 'Пароль должен быть не менее 8 символов.',

            'email.required'    => 'Почта является обязательным полем.',
            'email.email'   => 'Введена некорректная почта.',
            'email.max'     => 'Длина почты может быть не более 255 символов.',
            'email.unique'  => 'Данная почта уже занята.',

            'birthdate.required' => 'Дата обязательна к заполнению.',
            'birthdate.date' => 'Некорректный формат даты.',
            'birthdate.date_format' => 'Формат даты должен быть YYYY-MM-DD.',
        ];
    }
}
