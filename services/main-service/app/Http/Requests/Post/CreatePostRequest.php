<?php

namespace App\Http\Requests\Post;

use App\Rules\ValidatePostEntityType;
use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
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
            'text' => 'required|string|max:255',
            'media_url' => 'nullable|string',
            'entity_type' => ['required', new ValidatePostEntityType],
            'entity_id' => 'required|integer|min:1',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'text.required' => 'Text is required.',
            'text.string' => 'Text must be a string.',
            'text.max' => 'Text cannot exceed 255 characters.',
            'media_url.string' => 'Media URL must be a string.',
            'entity_type.required' => 'Entity type is required.',
            'entity_id.required' => 'Entity ID is required.',
            'entity_id.integer' => 'Entity ID must be an integer.',
            'entity_id.min' => 'Entity ID must be greater than 0.',
        ];
    }
}
