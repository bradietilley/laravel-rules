<?php

declare(strict_types=1);

use BradieTilley\Rules\Rule;
use Tests\Fixtures\AnExampleRuleImplementsInvokableRule;
use Tests\Fixtures\AnExampleRuleImplementsRule;
use Tests\Fixtures\AnExampleRuleImplementsValidationRule;

test('various rule types are supported', function (mixed $value) {
    $rule = Rule::make()->rule($value);

    expect($rule->toArray())->toBe([
        $value,
    ]);
})->with([
    'string' => fn () => 'required',
    'InvokableRule object' => fn () => new AnExampleRuleImplementsInvokableRule(),
    'Rule object' => fn () => new AnExampleRuleImplementsRule(),
    'ValidationRule object' => fn () => new AnExampleRuleImplementsValidationRule(),
]);
