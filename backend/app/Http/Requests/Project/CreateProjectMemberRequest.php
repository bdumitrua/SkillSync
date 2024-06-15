<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Dtoable;
use App\DTO\Project\CreateProjectMemberDTO;

class CreateProjectMemberRequest extends FormRequest
{
    use Dtoable;

    protected string $dtoClass = CreateProjectMemberDTO::class;

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
            'additional' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'additional.required' => 'Поле дополнительной информации обязательно для заполнения.',
            'additional.string' => 'Поле дополнительной информации должно быть строкой.',
            'additional.max' => 'Поле дополнительной информации не должно превышать 255 символов.',
        ];
    }
}
