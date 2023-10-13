<?php

declare(strict_types=1);

use Tests\TestCase;

uses(Tests\TestCase::class)->in('Feature', 'Unit');

function getPropertiesFromObject(object $object, array $properties): array
{
    $all = [];

    foreach ($properties as $property) {
        expect($object)->toHaveProperty($property);

        $value = (new ReflectionProperty($object, $property))->getValue($object);

        $all[$property] = $value;
    }

    return $all;
}

function bool_rand(): bool
{
    return !! mt_rand(0, 1);
}

function clearHistory(): void
{
    TestCase::$history = [];
}

function addHistory(): void
{
    foreach (func_get_args() as $arg) {
        TestCase::$history[] = $arg;
    }
}
function getHistory(): array
{
    return TestCase::$history;
}
