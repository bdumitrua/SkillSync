<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Dtoable;
use App\Rules\EntityIdRule;
use App\DTO\LikeDTO;

class LikeRequest extends FormRequest
{
    use Dtoable;

    protected string $dtoClass = LikeDTO::class;

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
            'likeableType' => 'required|in:post,comment,project',
            'likeableId' => [new EntityIdRule()],
        ];
    }
}
