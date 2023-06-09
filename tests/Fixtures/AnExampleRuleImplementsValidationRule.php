<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AnExampleRuleImplementsValidationRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }
}
