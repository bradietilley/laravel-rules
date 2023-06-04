<?php

declare(strict_types=1);

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
