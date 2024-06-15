<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Dtoable;
use App\DTO\Project\UpdateProjectDTO;

class UpdateProjectRequest extends FormRequest
{
    use Dtoable;

    protected string $dtoClass = UpdateProjectDTO::class;

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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coverUrl' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле названия проекта обязательно для заполнения.',
            'name.string' => 'Поле названия проекта должно быть строкой.',
            'name.max' => 'Поле названия проекта не должно превышать 255 символов.',

            'description.string' => 'Поле описания проекта должно быть строкой.',
            'coverUrl.string' => 'Поле URL обложки должно быть строкой.',
        ];
    }
}
