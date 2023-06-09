<?php

declare(strict_types=1);

namespace Tests\Fixtures\Binding;

use BradieTilley\Rules\Rule;

class CustomRuleClass extends Rule
{
    public function someCustomRule(): static
    {
        return $this->push('some_custom_rule');
    }
}
