<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Ensure a rating value stays between 0.5 and 5
 * and only increments in half-star steps.
 */
class HalfStarRating implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_numeric($value)) {
            $fail('The :attribute must be a number.');
            return;
        }

        $numericValue = (float) $value;

        if ($numericValue < 0.5 || $numericValue > 5) {
            $fail('The :attribute must be between 0.5 and 5.');
            return;
        }

        $scaled = $numericValue * 2;
        if (abs($scaled - round($scaled)) > 1e-6) {
            $fail('The :attribute must use half-star increments (0.5).');
        }
    }
}

