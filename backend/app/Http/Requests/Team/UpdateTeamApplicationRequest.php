<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\EnumValue;
use App\Enums\TeamApplicationStatus;

class UpdateTeamApplicationRequest extends FormRequest
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
            'status' => ['required', 'string', new EnumValue(TeamApplicationStatus::class)],
        ];
    }

    public function messages()
    {
        return [
            'status.required' => 'Status is required.',
            'status.string' => 'Status must be string.',
        ];
    }
}
