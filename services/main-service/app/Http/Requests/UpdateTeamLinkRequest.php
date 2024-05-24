<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamLinkRequest extends FormRequest
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
            'name' => 'required|string|max:30',
            'url' => 'required|url',
            'is_private' => 'required|boolean',
            'text' => 'nullable|string|max:30',
            'icon_type' => 'nullable|string|max:30',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'name.max' => 'Name cannot exceed 30 characters.',
            'url.required' => 'URL is required.',
            'url.url' => 'Invalid URL format.',
            'is_private.required' => 'IsPrivate is required.',
            'text.max' => 'Text cannot exceed 30 characters.',
            'icon_type.max' => 'IconType cannot exceed 30 characters.',
        ];
    }
}
