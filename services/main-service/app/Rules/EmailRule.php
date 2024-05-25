<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $isValidEmail = !empty($value)
            && is_string($value) && strlen($value) <= 255
            && filter_var($value, FILTER_VALIDATE_EMAIL);

        if (!$isValidEmail) {
            $fail("The :attribute must be a valid email address.");
        }
    }
}
