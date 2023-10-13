<?php

namespace BradieTilley\Rules\Haltable;

use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use Throwable;

/**
 * Supplements the ValidationRule interface with a cleaner interface
 * for returning early with failures or passes in rule classes.
 *
 * Substitute the `validate` method in your rule with a `run` methhod
 * and drop the redundant `$fail` argument.
 *
 * @mixin ValidationRule
 */
trait HaltableRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $this->run($attribute, $value);
        } catch (RulePassed) {
            return;
        } catch (RuleFailed $failed) {
            $fail($failed->getMessage());
        }
    }

    /**
     * Pass this validation rule and continue on to the next rule or field
     */
    public function passed(): never
    {
        throw RulePassed::make();
    }

    /**
     * Fail this validation rule and continue on to the next rule or field
     */
    public function failed(string $message): never
    {
        throw RuleFailed::make($message);
    }

    /**
     * Run the callback and treat any exception as a failure, using the
     * exception error message (verbatim).
     */
    public function catch(Closure $callback): void
    {
        try {
            $callback();
        } catch (Throwable $exception) {
            $this->failed($exception->getMessage());
        }
    }
}
