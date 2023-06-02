<?php

use BradieTilley\Rules\Rule;

beforeAll(function () {
    Rule::withHistory();
});

afterAll(function () {
    Rule::withoutHistory();
    Rule::resetHistory();
});

test('rule be asserted', function () {
    Rule::make('abc')->required()->string()->min(5)->max(255);
    Rule::make('def')->nullable()->integer()->min(0)->max(999);

    Rule::assertApplied('abc', [
        'required',
        'string',
        'min:5',
        'max:255',
    ]);

    Rule::assertApplied('def', [
        'integer',
        'min:0',
        'max:999',
    ]);

    Rule::assertNotApplied('ghi');

    Rule::assertNotApplied('abc', 'nullable');
    Rule::assertNotApplied('abc', 'integer');
});
