<?php

use BradieTilley\Rules\Facades\Rule;

test('rule be asserted', function () {
    Rule::make('abc')->required()->string()->min(5)->max(255);
    Rule::make('def')->nullable()->integer()->min(0)->max(999);

    expect(Rule::assertDefined('abc'))->toBe(true);
    expect(Rule::assertDefined('def'))->toBe(true);
    expect(Rule::assertDefined('ghi'))->toBe(false);

    //
})->todo();
