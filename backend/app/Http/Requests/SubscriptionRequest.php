<?php

namespace App\Http\Requests;

use App\DTO\SubscriptionDTO;
use App\Rules\EntityIdRule;
use App\Traits\Dtoable;
use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{
    use Dtoable;

    protected string $dtoClass = SubscriptionDTO::class;

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
            'entityType' => 'required|in:user,team',
            'entityId' => [new EntityIdRule()],
        ];
    }
}
