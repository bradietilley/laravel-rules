<?php

declare(strict_types=1);

use BradieTilley\Rules\Rule;

test('rule arguments can be compiled to string', function (array $input, string $expect) {
    $reflection = new ReflectionMethod(Rule::class, 'arguments');

    $actual = $reflection->invoke(null, ...$input);
    expect($actual)->toBe($expect);
})->with([
    'null' => fn () => [
        'input' => [null],
        'expect' => '',
    ],
    'empty string' => fn () => [
        'input' => [''],
        'expect' => '',
    ],
    'standard string' => fn () => [
        'input' => ['some value'],
        'expect' => ':some value',
    ],
    'standard string with different prefix' => fn () => [
        'input' => ['some value', ','],
        'expect' => ',some value',
    ],
    'a datetime object' => fn () => [
        'input' => [new DateTime('2023-01-02')],
        'expect' => ':2023-01-02',
    ],
    'an array of strings' => fn () => [
        'input' => [
            [ 'a', 'b', 'c', ]
        ],
        'expect' => ':a,b,c',
    ],
    'an array of strings with different prefix' => fn () => [
        'input' => [
            [ 'a', 'b', 'c', ],
            ',',
        ],
        'expect' => ',a,b,c',
    ],
    'an array of integers' => fn () => [
        'input' => [
            [ 1, 2, 3, ]
        ],
        'expect' => ':1,2,3',
    ],
    'an array of integers with different prefix' => fn () => [
        'input' => [
            [ 1, 2, 3, ],
            ','
        ],
        'expect' => ',1,2,3',
    ],
    'an array of datetime objects' => fn () => [
        'input' => [
            [ new DateTime('2023-01-02'), new DateTime('2023-01-03'), new DateTime('2023-01-04'), ]
        ],
        'expect' => ':2023-01-02,2023-01-03,2023-01-04',
    ],
    'an array of datetime objects with different prefix' => fn () => [
        'input' => [
            [ new DateTime('2023-01-02'), new DateTime('2023-01-03'), new DateTime('2023-01-04'), ],
            ','
        ],
        'expect' => ',2023-01-02,2023-01-03,2023-01-04',
    ],
]);
