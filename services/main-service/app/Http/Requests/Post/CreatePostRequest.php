<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidatePostEntityType;
use App\Rules\EntityIdRule;

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
            'entity_id' => [new EntityIdRule()],
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
        ];
    }
}
