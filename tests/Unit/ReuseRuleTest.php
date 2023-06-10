<?php

declare(strict_types=1);

use BradieTilley\Rules\Rule;

test('reuse rule with closure', function () {
    $reuseRule = function (Rule $rule) {
        $rule->integer()->max(10)->min(5);
    };

    $rule = Rule::make()->required()->with($reuseRule);
    expect($rule->toArray())->toEqual([
        'required',
        'integer',
        'max:10',
        'min:5',
    ]);
});

test('reuse rule with traditional callable', function () {
    $rule = Rule::make()->required()->with(Closure::fromCallable('reuseRule'));
    expect($rule->toArray())->toEqual([
        'required',
        'string',
        'email',
    ]);
});

test('reuse rule with first class callable', function () {
    $rule = Rule::make()->required()->with(reuseRule(...));
    expect($rule->toArray())->toEqual([
        'required',
        'string',
        'email',
    ]);
});

test('reuse rule with invoke class', function () {
    $rule = Rule::make()->required()->with(new ReuseRule());
    expect($rule->toArray())->toEqual([
        'required',
        'array',
        'min:20',
    ]);
});

function reuseRule(Rule $rule): void
{
    $rule->string()->email();
}

class ReuseRule
{
    public function __invoke(Rule $rule): void
    {
        $rule->array()->min(20);
    }
}
