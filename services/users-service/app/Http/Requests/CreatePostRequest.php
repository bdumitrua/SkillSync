<?php

namespace App\Http\Requests;

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
}
