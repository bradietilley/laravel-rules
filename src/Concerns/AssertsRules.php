<?php

namespace BradieTilley\Rules\Concerns;

use BradieTilley\Rules\Rule;
use Closure;
use Exception;
use Illuminate\Contracts\Validation\Rule as RuleContract;
use Illuminate\Contracts\Validation\ValidationRule as ValidationRuleContract;
use PHPUnit\Framework\Assert;

/**
 * @mixin Rule
 */
trait AssertsRules
{
    /** @var array<Rule> */
    protected static array $history = [];

    protected static bool $historyEnabled = false;

    /**
     * Enable history recording
     */
    public static function withHistory(): void
    {
        static::$historyEnabled = true;
    }

    /**
     * Enable history recording
     */
    public static function withoutHistory(): void
    {
        static::$historyEnabled = false;
    }

    /**
     * Reset the recorded history
     */
    public static function resetHistory(): void
    {
        static::$history = [];
    }

    /**
     * Push to the recorded history
     */
    public static function pushHistory(Rule $rule): void
    {
        if (static::$historyEnabled === false) {
            return;
        }

        static::$history[] = $rule;
    }

    /**
     * Filter the history array by those that match the field name
     *
     * @param array<Rule> $history
     * @return array<Rule>
     */
    private static function filterHistoryByFieldName(array $history, string $field): array
    {
        return array_filter($history, fn (Rule $rule) => $rule->field() === $field);
    }

    /**
     * Filter the history array by those that match the rule name
     *
     * @param array<Rule> $history
     * @return array<Rule>
     */
    private static function filterHistoryByRuleName(array $history, string $name): array
    {
        return array_filter($history, function (Rule $rule) use ($name) {
            foreach ($rule as $ruleName) {
                /** @var RuleContract|ValidationRuleContract|string $ruleName */
                $ruleName = is_string($ruleName) ? $ruleName : get_class($ruleName);

                if ($ruleName === $name) {
                    return true;
                }
            }

            return false;
        });
    }

    public static function assertApplied(string $field, array|string $rule = null, Closure $callback = null): void
    {
        if (! static::$historyEnabled) {
            throw new Exception('Cannot assert rule history unless history is enabled');
        }

        if ($rule === null) {
            Assert::assertTrue(
                self::wasApplied($field, null, $callback),
                'Failed asserting that the `%s` field had any rules applied',
            );

            return;
        }

        $rules = is_string($rule) ? [$rule] : $rule;

        foreach ($rules as $rule) {
            $error = sprintf(
                'Failed asserting that the `%s` field had the `%s` rule applied',
                $field,
                $rule,
            );

            Assert::assertTrue(
                self::wasApplied($field, $rule, $callback),
                $error,
            );
        }
    }

    public static function assertNotApplied(string $field, array|string $rule = null, Closure $callback = null)
    {
        if ($rule === null) {
            Assert::assertTrue(
                self::wasNotApplied($field, null, $callback),
                'Failed asserting that the `%s` field did not have any rules applied',
            );

            return;
        }

        $rules = is_string($rule) ? [$rule] : $rule;

        foreach ($rules as $rule) {
            $error = sprintf(
                'Failed asserting that the `%s` field did not have the `%s` rule applied',
                $field,
                $rule,
            );

            Assert::assertTrue(
                self::wasNotApplied($field, $rule, $callback),
                $error,
            );
        }
    }

    public static function wasApplied(string $field, string $rule = null, Closure $callback = null): bool
    {
        $history = static::$history;
        $history = static::filterHistoryByFieldName($history, $field);

        if ($rule !== null) {
            $history = static::filterHistoryByRuleName($history, $rule);
        }

        if (empty($history)) {
            return false;
        }

        if ($callback === null) {
            return true;
        }

        foreach ($history as $rule) {
            if ($callback($rule)) {
                return true;
            }
        }

        return false;
    }

    public static function wasNotApplied(string $field, string $rule = null, Closure $callback = null): bool
    {
        return ! self::wasApplied($field, $rule, $callback);
    }
}
