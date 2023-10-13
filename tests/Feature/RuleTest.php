<?php

declare(strict_types=1);

use BradieTilley\Rules\Rule;
use Tests\Fixtures\Binding\CustomRuleClass;

test('multiple rules can be applied to a rule', function () {
    $rule = Rule::make();
    $rule->required()->string()->min(5)->max(255);

    expect($rule->toArray())->toBe([
        'required',
        'string',
        'min:5',
        'max:255',
    ]);
});

test('you can modify the Rule class to use by swapping the binding', function () {
    Rule::using(Rule::class);
    expect(Rule::make())->toBeInstanceOf(Rule::class);

    Rule::using(CustomRuleClass::class);
    expect(Rule::make())->toBeInstanceOf(CustomRuleClass::class);

    Rule::using(Rule::class);
    expect(Rule::make())->toBeInstanceOf(Rule::class);

    Rule::using(CustomRuleClass::class);
    expect(Rule::make())->toBeInstanceOf(CustomRuleClass::class);

    Rule::usingDefault();
    expect(Rule::make())->toBeInstanceOf(Rule::class);
});
