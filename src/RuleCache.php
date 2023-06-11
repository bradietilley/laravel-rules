<?php

namespace BradieTilley\Rules;

class RuleCache
{
    /** @var array<string, array<string, Rule>> */
    public static array $cache = [];

    public static function reset(): void
    {
        self::$cache = [];
    }
}
