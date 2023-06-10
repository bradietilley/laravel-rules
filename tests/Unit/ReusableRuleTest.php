<?php

declare(strict_types=1);

use BradieTilley\Rules\Rule;

test('reuse rule with closure', function () {
    $reusableRule = function (Rule $rule) {
        $rule->integer()->max(10)->min(5);
    };

    $rule = Rule::make()->required()->with($reusableRule);
    expect($rule->toArray())->toEqual([
        'required',
        'integer',
        'max:10',
        'min:5',
    ]);
});

test('reuse rule with traditional callable', function () {
    $rule = Rule::make()->required()->with(Closure::fromCallable('reusableRule'));
    expect($rule->toArray())->toEqual([
        'required',
        'string',
        'email',
    ]);
});

test('reuse rule with first class callable', function () {
    $rule = Rule::make()->required()->with(reusableRule(...));
    expect($rule->toArray())->toEqual([
        'required',
        'string',
        'email',
    ]);
});

test('reuse rule with invoke class', function () {
    $rule = Rule::make()->required()->with(new ReusableRule());
    expect($rule->toArray())->toEqual([
        'required',
        'array',
        'min:20',
    ]);
});

function reusableRule(Rule $rule): void
{
    $rule->string()->email();
}

class ReusableRule
{
    public function __invoke(Rule $rule): void
    {
        $rule->array()->min(20);
    }
}
