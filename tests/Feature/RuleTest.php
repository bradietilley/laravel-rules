<?php

use BradieTilley\Rules\Facades\Rule as RuleFacade;
use BradieTilley\Rules\Rule;
use Tests\Fixtures\Binding\CustomRuleClass;

test('Rule facade instantiation methods work', function (string $method, ?string $argument, bool $methodIsRule = false) {
    $arguments = $argument !== null ? [$argument] : [];
    $rule = RuleFacade::{$method}(...$arguments);

    /**
     * Method works and returns the rule
     */
    expect($rule)->toBeInstanceOf(Rule::class);

    /**
     * Some instantiation methods are rules themselves which should
     * result in those rules being added.
     */
    $expectedRules = $methodIsRule ? [ $method ] : [];
    expect($rule->toArray())->toBe($expectedRules);

    $expectedName = $argument;
    expect($rule->field())->toBe($expectedName);
})->with([
    'bail() with no arguments' => [ 'bail', null, true ],
    'exclude() with no arguments' => [ 'exclude', null, true ],
    'filled() with no arguments' => [ 'filled', null, true ],
    'make() with no rguments' => [ 'make', null, false ],
    'missing() with no arguments' => [ 'missing', null, true ],
    'nullable() with no arguments' => [ 'nullable', null, true ],
    'required() with no arguments' => [ 'required', null, true ],
    'sometimes() with no arguments' => [ 'sometimes', null, true ],
    'bail() with field argument' => [ 'bail', 'a_field', true ],
    'exclude() with field argument' => [ 'exclude', 'b_field', true ],
    'filled() with field argument' => [ 'filled', 'd_field', true ],
    'make() with field argument' => [ 'make', 'f_field', false ],
    'missing() with field argument' => [ 'missing', 'g_field', true ],
    'nullable() with field argument' => [ 'nullable', 'h_field', true ],
    'required() with field argument' => [ 'required', 'i_field', true ],
    'sometimes() with field argument' => [ 'sometimes', 'j_field', true ],
]);

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
    expect(RuleFacade::make())->toBeInstanceOf(Rule::class);
    expect(Rule::make())->toBeInstanceOf(Rule::class);

    Rule::using(CustomRuleClass::class);
    expect(RuleFacade::make())->toBeInstanceOf(CustomRuleClass::class);
    expect(Rule::make())->toBeInstanceOf(CustomRuleClass::class);

    Rule::using(Rule::class);
    expect(RuleFacade::make())->toBeInstanceOf(Rule::class);
    expect(Rule::make())->toBeInstanceOf(Rule::class);
});
