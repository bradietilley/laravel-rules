<?php

use BradieTilley\Rules\Rule;
use BradieTilley\Rules\RuleCache;
use SebastianBergmann\Timer\Timer;

test('rule cache test', function (int $iterations) {
    $runWithout = function (int $iterations) {
        foreach (range(1, $iterations) as $i) {
            $example = [
                'first_name' => Rule::make()
                    ->required()
                    ->string()
                    ->min(1)
                    ->max(100),
                'last_name' => Rule::make()
                    ->required()
                    ->string()
                    ->min(1)
                    ->max(100),
                'email' => Rule::make()
                    ->required()
                    ->string()
                    ->email(),
                'phone' => Rule::make()
                    ->required()
                    ->string()
                    ->min(9)
                    ->max(11),
                'avatar' => Rule::make()
                    ->required()
                    ->file(minKilobytes: 10, maxKilobytes: 10000, allowedMimetypes: [
                        'image/jpeg',
                        'image/png',
                    ]),
            ];
        }
    };

    $runWith = function (int $iterations) {
        RuleCache::reset();

        foreach (range(1, $iterations) as $i) {
            $example = Rule::cache('something', fn () => [
                'first_name' => Rule::make()
                    ->required()
                    ->string()
                    ->min(1)
                    ->max(100),
                'last_name' => Rule::make()
                    ->required()
                    ->string()
                    ->min(1)
                    ->max(100),
                'email' => Rule::make()
                    ->required()
                    ->string()
                    ->email(),
                'phone' => Rule::make()
                    ->required()
                    ->string()
                    ->min(9)
                    ->max(11),
                'avatar' => Rule::make()
                    ->required()
                    ->file(minKilobytes: 10, maxKilobytes: 10000, allowedMimetypes: [
                        'image/jpeg',
                        'image/png',
                    ]),
            ]);
        }
    };

    /**
     * Run both once to ensure any overhead caused by first
     * invocation/class loading is not counted in the timer
     * used for benchmarking.
     */
    $runWithout(1);
    $runWith(1);

    $timer = new Timer();
    $timer->start();
    $runWithout($iterations);
    $without = $timer->stop();

    $timer = new Timer();
    $timer->start();
    $runWith($iterations);
    $with = $timer->stop();

    dump([
        'iterations' => $iterations,
        'withoutCaching' => $withoutMs = $without->asMilliseconds(),
        'withCaching' => $withMs = $with->asMilliseconds(),
        'overhead' => ($withoutMs / $withMs),
    ]);

    expect(true)->toBeTrue();
})->with([
    '1 iteration' => 1,
    '5 iterations' => 5,
    '10 iterations' => 10,
    '50 iterations' => 50,
    '100 iterations' => 100,
    '500 iterations' => 500,
    '1000 iterations' => 1000,
]);
