<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTagRequest extends FormRequest
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
            'title' => 'required|string|min:2|max:20'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Название тэга является обязательным полем',
            'title.string' => 'Название тэга должно быть строкой',
            'title.min' => 'Название тэга должно содержать 2 и более символа',
            'title.max' => 'Название тэга должно быть не длиннее 20 символов',
        ];
    }
}
