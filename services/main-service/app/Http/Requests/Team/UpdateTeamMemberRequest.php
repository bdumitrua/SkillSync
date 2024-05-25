<?php

namespace App\Http\Requests\Team;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\EntityIdRule;

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
            'user_id' => [new EntityIdRule(), Rule::exists('users', 'id')],
            'is_moderator' => 'required|boolean',
            'team_id' => [new EntityIdRule(), Rule::exists('teams', 'id')],
            'about' => 'nullable|string|max:80',
        ];
    }

    public function messages()
    {
        return [
            'user_id.exists' => 'The selected user does not exist.',
            'is_moderator.required' => 'IsModerator is required.',
            'team_id.exists' => 'The selected team does not exist.',
            'about.max' => 'About cannot exceed 80 characters.',
        ];
    }
}
