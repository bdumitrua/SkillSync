<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

class EnumValue implements ValidationRule
{
    protected $enumClass;

    public function __construct($enumClass)
    {
        $this->enumClass = $enumClass;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $isValidValue = in_array($value, array_column($this->enumClass::cases(), 'value'));

        if (!$isValidValue) {
            $values = implode(', ', array_map(fn ($case) => $case->value, $this->enumClass::cases()));
            $fail("The :attribute field must be a valid value. Possible values are: $values.");
        }
    }
}
