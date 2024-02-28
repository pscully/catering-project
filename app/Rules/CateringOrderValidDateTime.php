<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Filament\Forms\Get;
use Illuminate\Contracts\Validation\ValidationRule;

class CateringOrderValidDateTime implements ValidationRule
{
    /**
     * Determine if the validation rule passes.
     *
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $tomorrow = Carbon::tomorrow()->startOfDay();
        $selectedDate = Carbon::parse($value)->startOfDay();

        if ($selectedDate->equalTo($tomorrow) && strtotime($value) < strtotime('12:00 PM')) {
            $fail('When placing a next day order, pickup time must be at 12:00 PM or later.');
        }
    }
}
