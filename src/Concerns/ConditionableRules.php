<?php

namespace BradieTilley\Rules\Concerns;

use BradieTilley\Rules\Rule;
use Closure;

/**
 * @mixin Rule
 */
trait ConditionableRules
{
    /**
     * @return $this
     */
    public function when(bool|Closure $value, Rule $rule, ?Rule $default = null): static
    {
        $value = $value instanceof Closure ? $value($this) : $value;

        return $this->merge($value ? $rule : $default);
    }

    /**
     * @return $this
     */
    public function unless(bool|Closure $value, Rule $rule, ?Rule $default = null): static
    {
        $value = $value instanceof Closure ? $value($this) : $value;

        return $this->merge((! $value) ? $rule : $default);
    }
}
