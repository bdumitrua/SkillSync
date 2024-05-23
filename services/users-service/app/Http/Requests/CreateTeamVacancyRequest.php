<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTeamVacancyRequest extends FormRequest
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
            'team_id' => ['required', 'integer', 'min:1', Rule::exists('teams', 'id')],
            'title' => 'required|string|max:30',
            'description' => 'required|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'team_id.required' => 'TeamId is required.',
            'team_id.integer' => 'TeamId must be an integer.',
            'team_id.min' => 'TeamId must be greater than 0.',
            'team_id.exists' => 'The selected team does not exist.',
            'title.required' => 'Title is required.',
            'title.max' => 'Title cannot exceed 30 characters.',
            'description.required' => 'Description is required.',
            'description.max' => 'Description cannot exceed 500 characters.',
        ];
    }
}
