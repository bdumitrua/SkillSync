<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Dtoable;
use App\DTO\Project\CreateProjectLinkDTO;

class CreateProjectLinkRequest extends FormRequest
{
    use Dtoable;

    protected string $dtoClass = CreateProjectLinkDTO::class;

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
            'url' => 'required|string',
            'text' => 'nullable|string|max:30',
            'iconType' => 'nullable|string|max:30',
        ];
    }

    public function messages(): array
    {
        return [
            'url.required' => 'Поле URL обязательно для заполнения.',
            'url.string' => 'Поле URL должно быть строкой.',
            'text.string' => 'Поле текста должно быть строкой.',
            'text.max' => 'Поле текста не должно превышать 30 символов.',
            'iconType.string' => 'Поле типа иконки должно быть строкой.',
            'iconType.max' => 'Поле типа иконки не должно превышать 30 символов.',
        ];
    }
}
