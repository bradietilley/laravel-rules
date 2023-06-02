<?php

namespace BradieTilley\Rules\Rules;

use BradieTilley\Rules\Rule;
use Illuminate\Contracts\Validation\Rule as RuleContract;
use Illuminate\Contracts\Validation\ValidationRule as ValidationRuleContract;

class RuleHistory
{
    protected static array $history = [];

    public static function resetHistory(): void
    {
        static::$history = [];
    }

    public static function pushHistory(Rule $object, string|ValidationRuleContract|RuleContract $rule): void
    {
        $rule = is_string($rule) ? $rule : get_class($rule);

        static::$history[] = [
            'name' => $object->field(),
            'rule' => $rule,
            'object' => $object,
        ];
    }
}
