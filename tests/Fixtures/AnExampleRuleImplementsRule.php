<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Illuminate\Contracts\Validation\Rule;

class AnExampleRuleImplementsRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return '';
    }
}
