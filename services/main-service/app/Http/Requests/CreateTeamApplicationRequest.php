<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTeamApplicationRequest extends FormRequest
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
            'text' => 'nullable|string|max:200',
            'team_id' => ['required', 'integer', 'min:1', Rule::exists('teams', 'id')],
            'vacancy_id' => ['required', 'integer', 'min:1', Rule::exists('team_vacancies', 'id')],
        ];
    }

    public function messages()
    {
        return [
            'text.max' => 'The text cannot be longer than 200 characters.',
            'team_id.required' => 'TeamId is required.',
            'team_id.integer' => 'TeamId must be an integer.',
            'team_id.min' => 'TeamId must be greater than 0.',
            'team_id.exists' => 'The selected team does not exist.',
            'vacancy_id.required' => 'VacancyId is required.',
            'vacancy_id.integer' => 'VacancyId must be an integer.',
            'vacancy_id.min' => 'VacancyId must be greater than 0.',
            'vacancy_id.exists' => 'The selected vacancy does not exist.',
        ];
    }
}
