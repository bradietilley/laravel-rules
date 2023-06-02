<?php

namespace BradieTilley\Rules;

use DateTimeInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Validation\Rule as RuleContract;
use Illuminate\Contracts\Validation\ValidationRule as ValidationRuleContract;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use Iterator;

class Rule implements Iterator, Arrayable
{
    use Concerns\CoreRules;
    use Concerns\CreatesRules;
    use Concerns\IteratesRules;
    use Conditionable;
    use Macroable;

    /**
     * @var array<string|ValidationRuleContract|RuleContract>
     */
    protected array $rules = [];

    public function __construct(protected ?string $field = null)
    {
    }

    /**
     * Make a set of rules keyed by each rule's field.
     *
     * @return array<string, Rule>
     */
    public static function fieldset(Rule ...$rules): array
    {
        $keyed = [];

        foreach ($rules as $rule) {
            $keyed[$rule->requireFieldName()] = $rule;
        }

        return $keyed;
    }

    /**
     * Set the field name for this Rule.
     *
     * This is only required if you're building a ruleset using
     * the `Rule::fields()` method or if you're planning on
     * running assertions against which fields have what rules.
     */
    public function field(?string $field = null): self|string|null
    {
        if ($field !== null) {
            $this->field = $field;
        }

        return $this->field;
    }

    /**
     * Get the field name and abort if name not supplied
     */
    public function requireFieldName(): string
    {
        $field = $this->field;

        if ($field === null) {
            throw new \Exception('Rule must have a field in order to reference rule field');
        }

        return $field;
    }

    /**
     * Add a rule
     *
     * @return $this
     */
    public function rule(string|ValidationRuleContract|RuleContract|null $rule): self
    {
        if ($rule === null) {
            return $this;
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

        return ($value !== null && $value !== '') ? ($prefix.$value) : '';
    }

    /**
     * @return array<string|ValidationRuleContract|RuleContract>
     */
    public function toArray(): array
    {
        return $this->rules;
    }
}
