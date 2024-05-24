<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeamMemberRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'min:1', Rule::exists('users', 'id')],
            'is_moderator' => 'required|boolean',
            'team_id' => ['required', 'integer', 'min:1', Rule::exists('teams', 'id')],
            'about' => 'nullable|string|max:80',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'UserId is required.',
            'user_id.integer' => 'UserId must be an integer.',
            'user_id.min' => 'UserId must be greater than 0.',
            'user_id.exists' => 'The selected user does not exist.',
            'is_moderator.required' => 'IsModerator is required.',
            'team_id.required' => 'TeamId is required.',
            'team_id.integer' => 'TeamId must be an integer.',
            'team_id.min' => 'TeamId must be greater than 0.',
            'team_id.exists' => 'The selected team does not exist.',
            'about.max' => 'About cannot exceed 80 characters.',
        ];
    }
}
