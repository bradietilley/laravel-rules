<?php

use BradieTilley\Rules\Rule;
use BradieTilley\Rules\RuleCache;
use Illuminate\Validation\Rules\File;
use SebastianBergmann\Timer\Timer;

test('rule cache test', function (bool $withFileRule = false) {
    $runs = [
        '1 iteration' => 1,
        '5 iterations' => 5,
        '10 iterations' => 10,
        '50 iterations' => 50,
        '100 iterations' => 100,
        '500 iterations' => 500,
        '1000 iterations' => 1000,
    ];

    $runOriginal = function (int $iterations) use ($withFileRule) {
        foreach (range(1, $iterations) as $i) {
            $example = [
                'first_name' => [
                    'required',
                    'string',
                    'min:1',
                    'max:100',
                ],
                'last_name' => [
                    'required',
                    'string',
                    'min:1',
                    'max:100',
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                ],
                'phone' => [
                    'required',
                    'string',
                    'min:9',
                    'max:11',
                ],
                'avatar' => [
                    'required',
                    (! $withFileRule) ? 'string' : File::types([
                        'image/jpeg',
                        'image/png',
                    ])->min(10)->max(10000),
                ],
            ];
        }

        return $example;
    };

    $runWithout = function (int $iterations) use ($withFileRule) {
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
                'avatar' => (! $withFileRule) ? Rule::make()->required()->string() : Rule::make()
                    ->required()
                    ->file(minKilobytes: 10, maxKilobytes: 10000, allowedMimetypes: [
                        'image/jpeg',
                        'image/png',
                    ]),
            ];
        }

        return $example;
    };

    $runWith = function (int $iterations) use ($withFileRule) {
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
                'avatar' => (! $withFileRule) ? Rule::make()->required()->string() : Rule::make()
                    ->required()
                    ->file(minKilobytes: 10, maxKilobytes: 10000, allowedMimetypes: [
                        'image/jpeg',
                        'image/png',
                    ]),
            ]);
        }

        return $example;
    };

    foreach ($runs as $iterations) {
        $runWith(1);
        $runWithout(1);
        $runOriginal(1);

        $timer = new Timer();
        $timer->start();
        $result = $runWith($iterations);
        $with = $timer->stop();

        $timer = new Timer();
        $timer->start();
        $result = $runWithout($iterations);
        $without = $timer->stop();

        $timer = new Timer();
        $timer->start();
        $result = $runOriginal($iterations);
        $original = $timer->stop();

        $sub = $withFileRule ? 'with a File rule' : 'without a File rule';

        printBarGraphTiming("Iterations ($sub): {$iterations}", [
            'Original without Laravel Rules' => $original->asNanoseconds(),
            'Laravel Rules without Caching' => $without->asNanoseconds(),
            'Laravel Rules with Caching On' => $with->asNanoseconds(),
        ], symbol: 'n');
    }

    expect(true)->toBe(true);
})->group('performance')->with([
    'without a File rule' => false,
    'with a File rule' => true,
]);
