<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EntityIdRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $isValidEntityId = !empty($value) && is_int($value) && $value >= 1;

        if (!$isValidEntityId) {
            $fail("The :attribute must be a positive integer address.");
        }
    }
}
