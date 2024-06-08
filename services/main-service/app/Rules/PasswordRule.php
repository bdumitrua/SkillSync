<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

class PasswordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $isValidPassword = !empty($value) && is_string($value) && strlen($value) >= 8;

        if (!$isValidPassword) {
            $fail("Длина пароля должна быть 8 и более символов.");
        }
    }
}
