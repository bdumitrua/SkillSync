<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Dtoable;
use App\Rules\EntityIdRule;
use App\DTO\Post\CreatePostDTO;

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
            'authorType' => 'required|in:user,team',
            'authorId' => [new EntityIdRule()],
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
            'authorType.required' => 'Author type is required.',
            'authorType.int' => 'Author type can be user or team.',
        ];
    }
}
