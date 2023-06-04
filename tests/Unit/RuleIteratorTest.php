<?php

use BradieTilley\Rules\Rule;
use Tests\Fixtures\AnExampleRuleImplementsInvokableRule;
use Tests\Fixtures\AnExampleRuleImplementsRule;
use Tests\Fixtures\AnExampleRuleImplementsValidationRule;

test('rules can be iterated', function () {
    $rule = Rule::make();

    $testValidationRule = new AnExampleRuleImplementsValidationRule();
    $testInvokableRule = new AnExampleRuleImplementsInvokableRule();
    $testRule = new AnExampleRuleImplementsRule();

    $rule->required()
        ->string()
        ->min(5)
        ->max(255)
        ->rule(null)
        ->rule([
            'email',
        ])
        ->rule($testValidationRule)
        ->rule($testInvokableRule)
        ->rule($testRule);

    $actual = [];

    foreach ($rule as $key => $value) {
        $actual[$key] = $value;
    }

    expect($actual)->toBe([
        'required',
        'string',
        'min:5',
        'max:255',
        'email',
        $testValidationRule,
        $testInvokableRule,
        $testRule,
    ]);
});
