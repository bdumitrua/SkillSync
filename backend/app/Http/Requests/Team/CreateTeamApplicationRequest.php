<?php

namespace App\Http\Requests\Team;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Dtoable;
use App\Rules\EntityIdRule;
use App\DTO\Team\CreateTeamApplicationDTO;

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
        ];
    }

    public function messages()
    {
        return [
            'text.string' => 'The text should be string.',
            'text.max' => 'The text cannot be longer than 200 characters.',
        ];
    }
}
