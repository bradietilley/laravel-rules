<?php

namespace BradieTilley\Rules\Concerns;

use BradieTilley\Rules\Rule;
use Closure;

/**
 * Easily add conditional rules to the current rule object based
 * on a given condition. Matches a similar signature to Laravel's
 * Conditionable trait except it accepts Rule objects instead of
 * closures for the callback/default parameters.
 *
 * Example:
 *
 *      public function rules(): array
 *      {
 *          return [
 *              'name' => Rule::make()
 *                  ->when(
 *                      $this->method() === 'PUT',
 *                      Rule::make()->sometimes(),
 *                      Rule::make()->required()
 *                  )
 *                  ->string()
 *                  ->min(1)
 *                  ->min(10),
 *          ];
 *      }
 *
 * @mixin Rule
 */
trait ConditionalRules
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
