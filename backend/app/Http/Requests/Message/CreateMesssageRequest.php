<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Dtoable;
use App\DTO\Message\CreateMesssageDTO;

class CreateMesssageRequest extends FormRequest
{
    use Dtoable;

    protected string $dtoClass = CreateMesssageDTO::class;

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
            "text" => 'required|string',
        ];
    }
}
