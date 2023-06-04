<?php

declare(strict_types=1);

use BradieTilley\Rules\Rule;

if (! function_exists('rule')) {
    /**
     * Create a new validation rule object
     */
    function rule(): Rule
    {
        return Rule::make();
    }
}
