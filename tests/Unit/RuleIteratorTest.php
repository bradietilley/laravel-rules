<?php

use BradieTilley\Rules\Facades\Rule;

test('rules can be iterated', function () {
    $rule = Rule::make();
    $rule->required()->string()->min(5)->max(255);

    $actual = [];

    foreach ($rule as $key => $value) {
        $actual[$key] = $value;
    }

    expect($actual)->toBe([
        'required',
        'string',
        'min:5',
        'max:255',
    ]);
});
