<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RemoveTeamMemberRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'UserId is required.',
            'user_id.integer' => 'UserId must be an integer.',
            'user_id.min' => 'UserId must be greater than 0.',
            'user_id.exists' => 'The selected user does not exist.',
        ];
    }
}
