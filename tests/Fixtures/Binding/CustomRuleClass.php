<?php

namespace Tests\Fixtures\Binding;

use BradieTilley\Rules\Rule;

class CustomRuleClass extends Rule
{
    public function someCustomRule(): self
    {
        return $this->push('some_custom_rule');
    }
}
