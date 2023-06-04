<?php

declare(strict_types=1);

namespace BradieTilley\Rules\Concerns;

use BradieTilley\Rules\Rule;

/**
 * @mixin Rule
 *
 * @template TRule of Rule
 */
trait CreatesRules
{
    /**
     * @var class-string<TRule>
     */
    protected static string $using = Rule::class;

    /**
     * @param class-string<TRule> $class
     */
    public static function using(string $class): void
    {
        self::$using = $class;
    }

    /**
     * Create rules using the default Rule class
     */
    public static function usingDefault(): void
    {
        self::$using = Rule::class;
    }

    /**
     * Make a new instance.
     *
     * @return TRule
     */
    public static function make(?string $field = null): Rule
    {
        $class = self::$using;
        $instance = new $class($field);

        assert($instance instanceof Rule);

        return $instance;
    }
}
