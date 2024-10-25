<?php

declare(strict_types=1);

namespace BradieTilley\Rules;

use DateTimeInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Validation\InvokableRule as InvokableRuleContract;
use Illuminate\Contracts\Validation\Rule as RuleContract;
use Illuminate\Contracts\Validation\ValidationRule as ValidationRuleContract;
use Illuminate\Support\Traits\Macroable;
use Iterator;

/**
 * @implements Iterator<int, mixed>
 * @implements Arrayable<int, mixed>
 * @template TRule of Rule
 */
class Rule implements Iterator, Arrayable
{
    use Concerns\CoreRules;
    use Concerns\CreatesRules;
    use Concerns\IteratesRules;
    use Concerns\ConditionalRules;
    use Macroable;

    /**
     * @var array<string|InvokableRuleContract|RuleContract|ValidationRuleContract>
     */
    protected array $rules = [];

    /**
     * Add a rule
     *
     * @param string|InvokableRuleContract|RuleContract|ValidationRuleContract|Rule|array<string|InvokableRuleContract|RuleContract|ValidationRuleContract|Rule>|null $rule
     */
    public function rule(string|InvokableRuleContract|RuleContract|ValidationRuleContract|Rule|array|null $rule): static
    {
        if ($rule === null) {
            return $this;
        }

        if (is_array($rule)) {
            foreach ($rule as $ruleToAdd) {
                $this->rule($ruleToAdd);
            }

            return $this;
        }

        if ($rule instanceof Rule) {
            return $this->merge($rule);
        }

        $this->rules[] = $rule;

        return $this;
    }

    /**
     * Standardise the value for string concatenation.
     */
    protected static function standardise(int|string|DateTimeInterface $date): string
    {
        if ($date instanceof DateTimeInterface) {
            return $date->format('Y-m-d');
        }

        return (string) $date;
    }

    /**
     * Prepare the value for string concatenation. When the value is not null, the given prefix
     * will be applied.
     *
     * @param array<int, int|string|DateTimeInterface>|int|string|DateTimeInterface|null $value
     * @phpstan-ignore-next-line - Method BradieTilley\Rules\Rule::arguments() has parameter $value with no value type specified in iterable type array.
     */
    protected static function arguments(array|string|DateTimeInterface|null $value, string $prefix = ':'): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        if (is_array($value)) {
            $value = array_map(self::standardise(...), $value);
            $value = implode(',', $value);
        } else {
            $value = self::standardise($value);
        }

        return $value !== '' ? $prefix.$value : '';
    }

    /**
     * Cast the rule to array form, returning the underling rules
     *
     * @return array<string|InvokableRuleContract|RuleContract|ValidationRuleContract>
     */
    public function toArray(): array
    {
        return $this->rules;
    }

    /**
     * Merge the given Rule's underlying rules with this Rule.
     */
    public function merge(?Rule $rule): static
    {
        if ($rule !== null) {
            $this->rules = array_merge($this->rules, $rule->toArray());
        }

        return $this;
    }

    /**
     * Merge the given callable with this Rule. The callable
     * may be a Closure, function, method, or Invokable class
     * and must accept this Rule instance as the only argument
     */
    public function with(callable $callable): static
    {
        $callable($this);

        return $this;
    }
}
