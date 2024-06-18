<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Dtoable;
use App\Rules\EnumValue;
use App\Rules\EntityIdRule;
use App\Enums\ChatType;
use App\DTO\Message\CreateChatDTO;

class CreateChatRequest extends FormRequest
{
    use Dtoable;

    protected string $dtoClass = CreateChatDTO::class;

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
            'type' => ['required', 'string', new EnumValue(ChatType::class)],

            'firstUserId' => 'required_if:type,dialog|exists:users,id',
            'secondUserId' => 'required_if:type,dialog|exists:users,id',

            'adminType' => 'required_if:type,group|string|in:user,team',
            'adminId' => [
                'required_if:adminType,user|exists:users,id',
                'required_if:adminType,team|exists:teams,id'
            ],
            'name' => 'required_if:type,group|string|max:50',
            'avatarUrl' => 'nullable|string',
            'memberIds' => 'required_if:adminType,user|array',
            'memberIds.*' => 'distinct|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Type is required.',
            'type.string' => 'Type must be a string.',
            'type.in' => 'Type must be either dialog or group.',

            'firstUserId.required_if' => 'First user ID is required for dialogs.',
            'firstUserId.exists' => 'First user ID must exist in users.',

            'secondUserId.required_if' => 'Second user ID is required for dialogs.',
            'secondUserId.exists' => 'Second user ID must exist in users.',

            'adminType.required_if' => 'Admin type is required for groups.',
            'adminType.string' => 'Admin type must be a string.',
            'adminType.in' => 'Admin type must be either user or team.',

            'adminId.required_if' => [
                'Admin ID is required if admin type is user.',
                'Admin ID is required if admin type is team.'
            ],
            'adminId.exists' => [
                'Admin ID must exist in users if admin type is user.',
                'Admin ID must exist in teams if admin type is team.'
            ],

            'name.required_if' => 'Name is required for groups.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name cannot exceed 50 characters.',

            'avatarUrl.string' => 'Avatar URL must be a string.',

            'memberIds.required_if' => 'MemberIds are required for user groups.',
            'memberIds.array' => 'MemberIds must be an array.',
            'memberIds.*.distinct' => 'Each member ID must be unique.',
            'memberIds.*.exists' => 'Each member ID must exist in users.',
        ];
    }
}
