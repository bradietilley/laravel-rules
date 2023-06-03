<?php

use BradieTilley\Rules\Rule;

if (! function_exists('rule')) {
    /**
     * Create a new rule for a field.
     */
    function rule(string $field = null): Rule
    {
        return Rule::make($field);
    }
}

if (! function_exists('rules')) {
    /**
     * Make a set of rules keyed by each rule's field name
     *
     * @return array<Rule>
     */
    function rules(Rule ...$rules): array
    {
        return Rule::ruleset(...$rules);
    }
}
