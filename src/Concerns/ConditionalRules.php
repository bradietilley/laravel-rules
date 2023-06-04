<?php

namespace BradieTilley\Rules\Concerns;

use BradieTilley\Rules\Rule;
use Closure;
use Illuminate\Contracts\Validation\InvokableRule as InvokableRuleContract;
use Illuminate\Contracts\Validation\Rule as RuleContract;
use Illuminate\Contracts\Validation\ValidationRule as ValidationRuleContract;

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
     * Apply the given rule when the condition is met, if not, the default
     * rule will be applied (if supplied).
     *
     * @param string|InvokableRuleContract|RuleContract|ValidationRuleContract|Rule|array<string|InvokableRuleContract|RuleContract|ValidationRuleContract|Rule> $rule
     * @param string|InvokableRuleContract|RuleContract|ValidationRuleContract|Rule|array<string|InvokableRuleContract|RuleContract|ValidationRuleContract|Rule>|null $default
     * @return $this
     */
    public function when(
        bool|Closure $value,
        string|InvokableRuleContract|RuleContract|ValidationRuleContract|Rule|array $rule,
        string|InvokableRuleContract|RuleContract|ValidationRuleContract|Rule|array|null $default = null
    ): self {
        $value = $value instanceof Closure ? $value($this) : $value;

        return $this->rule($value ? $rule : $default);
    }

    /**
     * Apply the given rule unless the condition is met, if met, the default
     * rule will be applied (if supplied).
     *
     * @param string|InvokableRuleContract|RuleContract|ValidationRuleContract|Rule|array<string|InvokableRuleContract|RuleContract|ValidationRuleContract|Rule> $rule
     * @param string|InvokableRuleContract|RuleContract|ValidationRuleContract|Rule|array<string|InvokableRuleContract|RuleContract|ValidationRuleContract|Rule>|null $default
     * @return $this
     */
    public function unless(
        bool|Closure $value,
        string|InvokableRuleContract|RuleContract|ValidationRuleContract|Rule|array $rule,
        string|InvokableRuleContract|RuleContract|ValidationRuleContract|Rule|array|null $default = null
    ): self {
        $value = $value instanceof Closure ? $value($this) : $value;

        return $this->rule((! $value) ? $rule : $default);
    }
}
