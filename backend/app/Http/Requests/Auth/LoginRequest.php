<?php

namespace App\Http\Requests\Auth;

use App\Rules\EmailRule;
use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [new EmailRule()],
            'password' => [new PasswordRule()],
        ];
    }
}
