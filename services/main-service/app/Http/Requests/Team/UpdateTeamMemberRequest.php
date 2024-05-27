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
            'userId' => [new EntityIdRule(), Rule::exists('users', 'id')],
            'teamId' => [new EntityIdRule(), Rule::exists('teams', 'id')],
            'isModerator' => 'required|boolean',
            'about' => 'nullable|string|max:80',
        ];
    }

    public function messages()
    {
        return [
            'userId.exists' => 'The selected user does not exist.',
            'teamId.exists' => 'The selected team does not exist.',
            'isModerator.required' => 'IsModerator is required.',
            'about.max' => 'About cannot exceed 80 characters.',
        ];
    }
}
