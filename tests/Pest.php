<?php

declare(strict_types=1);

use function Termwind\render;

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

/**
 * @param array<string, int|float> $times
 */
function printBarGraphTiming(string $title, array $times, string $symbol = 'µ'): void
{
    render(<<<HTML
<div class="w-100 bg-green p-1 mt-3 mb-1">
    <div class="pl-2">$title</div>
</div>
</div>
HTML);

    $max = collect($times)->max();
    $base = null;

    foreach ($times as $key => $time) {
        $relative = ($time / $max) * 100;
        $remain = (100 - $relative);

        $timeRounded = str_pad((string) number_format($time, 0), 5, ' ', STR_PAD_LEFT);
        $relativePercent = null;

        if ($base === null) {
            $base = $time;
            $relativePercent = '0.00%';
        } else {
            $perc = ($time / $base);

            if ($perc > 1) {
                $perc--;
                $relativePercent = '+'.number_format($perc*100, 2).'%';
            } else {
                $perc = 1/$perc;
                $relativePercent = '-'.number_format($perc*100, 2).'%';
            }
        }

        render(<<<HTML
<div class="w-100 flex">
    <span class="w-50 pl-1">{$key}</span>
    <span class="w-30 pl-1 text-right">{$timeRounded} {$symbol}s</span>
    <span class="w-20 pl-1 text-right">{$relativePercent}</span>
</div>
HTML);

        $relativeRound = (int) round($relative);
        $remainRound = (int) round($remain);

        render(<<<HTML
<div class="w-full flex mb-1">
    <span class="bg-white w-{$relativeRound}"></span>
    <span class="bg-gray w-{$remainRound}"></span>
</div>
HTML);
    }
};
