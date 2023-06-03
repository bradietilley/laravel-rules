<?php

use BradieTilley\Rules\Rule;

test('you can add conditionable logic to the rule class', function (string $method, bool $condition, array $expect) {
    $rule = Rule::make()
        ->required()
        ->string()
        ->{$method}(
            $condition,
            fn (Rule $rule) => $rule->min(1)->max(10),
            fn (Rule $rule) => $rule->min(2)->max(9),
        );

    expect($rule)->toBeInstanceOf(Rule::class)
        ->toArray()->toBe($expect);
})->with([
    'when:true' => [
        'when',
        true,
        [
            'required',
            'string',
            'min:1',
            'max:10',
        ],
    ],
    'when:false' => [
        'when',
        false,
        [
            'required',
            'string',
            'min:2',
            'max:9',
        ],
    ],
    'unless:true' => [
        'unless',
        true,
        [
            'required',
            'string',
            'min:2',
            'max:9',
        ],
    ],
    'unless:false' => [
        'unless',
        false,
        [
            'required',
            'string',
            'min:1',
            'max:10',
        ],
    ],
]);
