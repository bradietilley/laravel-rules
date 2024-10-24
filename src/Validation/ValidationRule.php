<?php

namespace BradieTilley\Rules\Validation;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule as ValidationRuleContract;

abstract class ValidationRule implements ValidationRuleContract
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $result = $this->run($attribute, $value);

        if ($result instanceof FailMessage) {
            $fail($result->message);
        }
    }

    /**
     * Run the validation rule.
     */
    abstract public function run(string $attribute, mixed $value): ?FailMessage;

    /**
     * Pass this validation rule.
     *
     * Usage of this is purely cosmetic for readable code to see outcomes like fail, fail, pass
     */
    public function pass(): null
    {
        return null;
    }

    /**
     * Fail this validation rule and continue on to the next rule or field
     */
    public function fail(string $message): FailMessage
    {
        return new FailMessage($message);
    }
}
