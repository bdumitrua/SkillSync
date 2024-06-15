<?php

namespace App\Http\Requests\Team;

use App\DTO\Team\CreateTeamMemberDTO;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\EntityIdRule;
use App\Traits\Dtoable;

class CreateTeamMemberRequest extends FormRequest
{
    use Dtoable;

    protected string $dtoClass = CreateTeamMemberDTO::class;

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
            'isModerator' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'userId.exists' => 'The selected user does not exist.',
            'isModerator.required' => 'IsModerator is required.',
        ];
    }
}
