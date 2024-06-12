<?php

namespace App\Http\Requests\Message;

use App\DTO\Message\CreateMesssageDTO;
use App\Traits\CreateDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateMesssageRequest extends FormRequest
{
    use CreateDTO;

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
            "text" => 'required|string|max:1000',
        ];
    }
}
