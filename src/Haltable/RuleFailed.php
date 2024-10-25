<?php

namespace BradieTilley\Rules\Haltable;

use Exception;

/**
 * Internally used to return early (failure) when validating a `HaltableRule`
 *
 * @deprecated
 * @see BradieTilley\Rules\Validation\ValidationRule
 */
class RuleFailed extends Exception
{
    public static function make(string $error): self
    {
        return new self($error);
    }
}
