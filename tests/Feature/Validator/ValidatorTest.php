<?php

declare(strict_types=1);

use BradieTilley\Rules\Rule;
use Illuminate\Support\Facades\Validator;

test('a validator instance can use Rulesets for each field', function (array $input, ?string $errors) {
    $validator = Validator::make($input, [
        'foo' => Rule::make()->bail()->required()->string()->min(2)->max(20),
        'bar' => Rule::make()->bail()->nullable()->string()->in('pending', 'active'),
        'baz' => Rule::make()->bail()->sometimes()->dateFormat('Y-m-d')->before('today'),
    ]);
    $passes = $validator->passes();

    if ($errors === null) {
        expect($passes)->toBeTrue();

        return;
    }

    $errors = implode(',', array_keys($validator->errors()->messages()));
    expect($errors)->toBe($errors);
})->with([
    'passes' => fn () => [
        'input' => [
            'foo' => 'some value',
            'bar' => 'pending',
            'baz' => '2023-04-03',
        ],
        'errors' => null,
    ],
    'fails on foo' => fn () => [
        'input' => [
            'foo' => 123,
            'bar' => 'pending',
            'baz' => '2023-04-03',
        ],
        'errors' => 'foo',
    ],
    'fails on bar' => fn () => [
        'input' => [
            'foo' => 'some value',
            'bar' => 'complete',
            'baz' => '2023-04-03',
        ],
        'errors' => 'bar',
    ],
    'fails on baz' => fn () => [
        'input' => [
            'foo' => 'some value',
            'bar' => 'pending',
            'baz' => 'something',
        ],
        'errors' => 'baz',
    ],
]);
