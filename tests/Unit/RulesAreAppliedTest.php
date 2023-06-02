<?php

use BradieTilley\Rules\Rule;

it('applies the `accepted` rule', function () {
    $rule = Rule::make('my_field')->accepted();

    expect($rule->toArray())->toBe([
        'accepted',
    ]);
});
