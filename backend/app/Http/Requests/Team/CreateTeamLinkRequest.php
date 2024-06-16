<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Dtoable;
use App\DTO\Team\CreateTeamLinkDTO;

class CreateTeamLinkRequest extends FormRequest
{
    use Dtoable;

    protected string $dtoClass = CreateTeamLinkDTO::class;

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
            'url' => 'required',
            'isPrivate' => 'required|boolean',
            'text' => 'nullable|string|max:30',
            'iconType' => 'nullable|string|max:30',
        ];
    }

    public function messages()
    {
        return [
            'url.required' => 'URL is required.',
            'isPrivate.required' => 'IsPrivate is required.',
            'text.max' => 'Text cannot exceed 30 characters.',
            'iconType.max' => 'IconType cannot exceed 30 characters.',
        ];
    }
}
