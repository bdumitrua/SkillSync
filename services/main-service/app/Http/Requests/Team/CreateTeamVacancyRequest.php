<?php

namespace App\Http\Requests\Team;

use App\DTO\Team\CreateTeamVacancyDTO;
use App\Traits\CreateDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTeamVacancyRequest extends FormRequest
{
    use CreateDTO;

    protected string $dtoClass = CreateTeamVacancyDTO::class;

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
            'title' => 'required|string|max:30',
            'description' => 'required|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required.',
            'title.max' => 'Title cannot exceed 30 characters.',
            'description.required' => 'Description is required.',
            'description.max' => 'Description cannot exceed 500 characters.',
        ];
    }
}
