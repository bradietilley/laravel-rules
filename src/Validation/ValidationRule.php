<?php

namespace BradieTilley\Rules\Validation;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule as ValidationRuleContract;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * A clean base implementation of the `ValidationRule` interface, providing
 * a simple signature for running the valdiation rule as well as enforcing
 * either a success or failure result for increased readability and integrity.
 */
abstract class ValidationRule implements ValidationRuleContract
{
    public const UNKNOWN_ERROR = 'Unknown error';

    protected ?bool $success = null;

    protected ?string $failureMessage = null;

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $this->run($attribute, $value);

        if ($this->success === null) {
            report(new RuntimeException(
                $error = sprintf('No outcome was derived from rule class `%s`', Str::of(static::class)->before('@')->toString()),
            ));

            $this->success = false;
            $this->failureMessage = $error;
        }

        if ($this->success === false) {
            $fail($this->failureMessage ?? static::UNKNOWN_ERROR);
        }
    }

    /**
     * Run the validation rule.
     */
    abstract public function run(string $attribute, mixed $value): static;

    /**
     * Pass this validation rule.
     *
     * Usage of this is mostly cosmetic for readable code to see outcomes like fail, fail, pass,
     * but also guarantees that the result of a rule is explicitly a success or failure.
     */
    public function pass(): static
    {
        $this->success = true;

        return $this;
    }

    /**
     * Fail this validation rule and continue on to the next rule or field
     */
    public function fail(string $error): static
    {
        $this->success = false;
        $this->failureMessage = $error;

        return $this;
    }
}
