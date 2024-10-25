<?php

namespace BradieTilley\Rules\Haltable;

use Exception;

/**
 * Internally used to return early (pass) when validating a `HaltableRule`
 *
 * @deprecated
 * @see BradieTilley\Rules\Validation\ValidationRule
 */
class RulePassed extends Exception
{
    public static function make(): self
    {
        return new self();
    }
}
