<?php

use BradieTilley\Rules\Rule;

test('you can add macros to the rule class', function () {
    Rule::macro('australianPhoneNumber', function () {
        /** @var Rule $this */
        return $this->rule('regex:/^\+614\d{8}$/');
    });

    $rule = Rule::make()->required()->string()->australianPhoneNumber();

    expect($rule)->toBeInstanceOf(Rule::class)
        ->toArray()->toBe([
            'required',
            'string',
            'regex:/^\+614\d{8}$/',
        ]);
});
