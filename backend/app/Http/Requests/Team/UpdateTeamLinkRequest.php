<?php

namespace App\Http\Requests\Team;

use App\DTO\Team\UpdateTeamLinkDTO;
use App\Traits\Dtoable;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamLinkRequest extends FormRequest
{
    use Dtoable;

    protected string $dtoClass = UpdateTeamLinkDTO::class;

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
            'name' => 'required|string|max:30',
            'url' => 'required|url',
            'isPrivate' => 'required|boolean',
            'text' => 'nullable|string|max:30',
            'iconType' => 'nullable|string|max:30',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'name.max' => 'Name cannot exceed 30 characters.',
            'url.required' => 'URL is required.',
            'url.url' => 'Invalid URL format.',
            'isPrivate.required' => 'IsPrivate is required.',
            'text.max' => 'Text cannot exceed 30 characters.',
            'iconType.max' => 'IconType cannot exceed 30 characters.',
        ];
    }
}
