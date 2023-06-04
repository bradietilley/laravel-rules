<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Closure;
use Illuminate\Contracts\Validation\InvokableRule;

class AnExampleRuleImplementsInvokableRule implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function __invoke(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }
}
