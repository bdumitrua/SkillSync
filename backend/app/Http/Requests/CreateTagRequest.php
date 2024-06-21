<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Dtoable;
use App\Rules\EntityIdRule;
use App\DTO\CreateTagDTO;

class CreateTagRequest extends FormRequest
{
    use Dtoable;

    protected string $dtoClass = CreateTagDTO::class;

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
            'title' => 'required|string|min:2|max:20',
            'entityType' => 'required|in:user,team,post',
            'entityId' => [new EntityIdRule()],
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
