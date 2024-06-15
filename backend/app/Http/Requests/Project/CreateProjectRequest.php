<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Dtoable;
use App\Rules\EntityIdRule;
use App\DTO\Project\CreateProjectDTO;

class CreateProjectRequest extends FormRequest
{
    use Dtoable;

    protected string $dtoClass = CreateProjectDTO::class;

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
            'authorType' => 'required|in:user,team',
            'authorId' => ['required', new EntityIdRule()],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coverUrl' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'authorType.required' => 'Поле типа автора обязательно для заполнения.',
            'authorType.in' => 'Поле типа автора должно быть одним из следующих значений: user, team.',
            'authorId.required' => 'Поле ID автора обязательно для заполнения.',

            'name.required' => 'Поле названия проекта обязательно для заполнения.',
            'name.string' => 'Поле названия проекта должно быть строкой.',
            'name.max' => 'Поле названия проекта не должно превышать 255 символов.',

            'description.string' => 'Поле описания проекта должно быть строкой.',
            'coverUrl.string' => 'Поле URL обложки должно быть строкой.',
        ];
    }
}
