<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CateringOrderValidDateTime implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === '7:30 AM' || $value === '8:00 AM' || $value === '8:30 AM' || $value === '9:00') {
            $fail('The order time must be at least 9:30 AM when ordering next day.');
        }
    }
}
