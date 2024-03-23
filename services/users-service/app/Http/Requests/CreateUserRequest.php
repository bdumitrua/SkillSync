<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'firstName' => 'required|string|max:255',
            'secondName' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|max:255|unique:users',
            'birthDate' => 'required|date|date_format:Y-m-d',
        ];
    }

    public function messages()
    {
        return [
            'firstName.required' => 'Имя является обязательным полем.',
            'firstName.string'   => 'Имя должно быть строкой.',
            'firstName.max'      => 'Имя может быть не длиннее 255 символов.',

            'secondName.required' => 'Имя является обязательным полем.',
            'secondName.string'   => 'Имя должно быть строкой.',
            'secondName.max'      => 'Имя может быть не длиннее 255 символов.',

            'password.required' => 'Пароль обязателен к заполнению.',
            'password.string' => 'Пароль должен быть строкой.',
            'password.min' => 'Пароль должен быть не менее 8 символов.',

            'email.required'    => 'Почта является обязательным полем.',
            'email.email'   => 'Введена некорректная почта.',
            'email.max'     => 'Длина почты может быть не более 255 символов.',
            'email.unique'  => 'Данная почта уже занята.',

            'birthDate.required' => 'Дата обязательна к заполнению.',
            'birthDate.date' => 'Некорректный формат даты.',
            'birthDate.date_format' => 'Формат даты должен быть YYYY-MM-DD.',
        ];
    }
}
