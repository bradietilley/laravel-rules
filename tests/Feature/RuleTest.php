<?php

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

test('a ruleset can be created using Rule objects', function () {
    $ruleset = Rule::fieldset(
        Rule::make('a')->required(),
        Rule::make('b')->nullable(),
        Rule::make('c')->sometimes(),
        Rule::make('d')->missing(),
    );

    expect($ruleset)->toBeArray()->toHaveCount(4)->toHaveKeys([
        'a',
        'b',
        'c',
        'd',
    ]);

    $compiled = [];
    foreach ($ruleset as $field => $rule) {
        expect($rule)->toBeInstanceOf(Rule::class);

        $compiled[$field] = $rule->toArray();
    }

    expect($compiled)->toBe([
        'a' => ['required'],
        'b' => ['nullable'],
        'c' => ['sometimes'],
        'd' => ['missing'],
    ]);
});

test('you can modify the Rule class to use by swapping the binding', function () {
    Rule::using(Rule::class);
    expect(Rule::make())->toBeInstanceOf(Rule::class);

    Rule::using(CustomRuleClass::class);
    expect(Rule::make())->toBeInstanceOf(CustomRuleClass::class);

    Rule::using(Rule::class);
    expect(Rule::make())->toBeInstanceOf(Rule::class);
});
