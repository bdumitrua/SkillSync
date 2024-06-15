<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;
use App\DTO\Team\UpdateTeamDTO;
use App\Traits\Dtoable;

class UpdateTeamRequest extends FormRequest
{
    use Dtoable;

    protected string $dtoClass = UpdateTeamDTO::class;

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
            'avatar' => 'nullable|url',
            'description' => 'nullable|string|max:200',
            'email' => 'nullable|email',
            'site' => 'nullable|url',
            'chatId' => 'nullable|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name field is required.',
            'name.max' => 'Name cannot exceed 30 characters.',
            'avatar.url' => 'Invalid URL format for Avatar.',
            'description.max' => 'Description cannot exceed 200 characters.',
            'email.email' => 'Invalid Email format.',
            'site.url' => 'Invalid URL format for Site.',
            'chatId.integer' => 'ChatId must be an integer.',
            'chatId.min' => 'ChatId must be greater than 0.',
        ];
    }
}
