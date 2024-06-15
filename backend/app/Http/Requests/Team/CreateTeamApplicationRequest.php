<?php

namespace App\Http\Requests\Team;

use App\DTO\Team\CreateTeamApplicationDTO;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\EntityIdRule;
use App\Traits\Dtoable;

class CreateTeamApplicationRequest extends FormRequest
{
    use Dtoable;

    protected string $dtoClass = CreateTeamApplicationDTO::class;

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
            'teamId' => [new EntityIdRule(), Rule::exists('teams', 'id')],
            'vacancyId' => [new EntityIdRule(), Rule::exists('team_vacancies', 'id')],
        ];
    }

    public function messages()
    {
        return [
            'text.string' => 'The text should be string.',
            'text.max' => 'The text cannot be longer than 200 characters.',

            'teamId.exists' => 'The selected team does not exist.',
            'vacancyId.exists' => 'The selected vacancy does not exist.',
        ];
    }
}
