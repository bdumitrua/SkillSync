<?php

namespace App\Http\Requests\Post;

use App\DTO\Post\CreatePostDTO;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidatePostEntityType;
use App\Rules\EntityIdRule;
use App\Traits\Dtoable;

class CreatePostRequest extends FormRequest
{
    use Dtoable;

    protected string $dtoClass = CreatePostDTO::class;

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
            'mediaUrl' => 'nullable|string',
            'entityType' => 'required|in:user,team',
            'entityId' => [new EntityIdRule()],
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
            'mediaUrl.string' => 'Media URL must be a string.',
            'entityType.required' => 'Entity type is required.',
            'entityType.int' => 'Entity type can be user or team.',
        ];
    }
}
