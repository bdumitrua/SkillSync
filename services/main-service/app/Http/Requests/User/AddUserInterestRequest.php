<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AddUserInterestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|min:2|max:20'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Название интереса является обязательным полем',
            'title.string' => 'Название интереса должно быть строкой',
            'title.min' => 'Название интереса должно содержать 2 и более символа',
            'title.max' => 'Название интереса должно быть не длиннее 20 символов',
        ];
    }
}
