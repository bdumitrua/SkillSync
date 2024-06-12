<?php

namespace App\Http\Requests\User;

use App\DTO\User\CreateUserDTO;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PasswordRule;
use App\Rules\EmailRule;
use App\Traits\CreateDTO;

class CreateUserRequest extends FormRequest
{
    use CreateDTO;

    protected string $dtoClass = CreateUserDTO::class;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'password' => [new PasswordRule()],
            'email' => [new EmailRule(), Rule::unique('users', 'email')],
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
