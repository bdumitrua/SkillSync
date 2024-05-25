<?php

namespace App\Http\Requests\Auth;

use App\Rules\EmailRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [new EmailRule()],
        ];
    }
}
