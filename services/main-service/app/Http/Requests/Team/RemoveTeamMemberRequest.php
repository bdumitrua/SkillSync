<?php

namespace App\Http\Requests\Team;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\EntityIdRule;

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
            'user_id' => [new EntityIdRule(), Rule::exists('users', 'id')],
        ];
    }

    public function messages()
    {
        return [
            'user_id.exists' => 'The selected user does not exist.',
        ];
    }
}
