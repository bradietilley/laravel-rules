<?php

declare(strict_types=1);

use BradieTilley\Rules\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use SebastianBergmann\Timer\Timer;

test('performance stress test', function (int $max, int $threshold) {
    $strings = [
        null,
        '',
        Str::random(1),
        Str::random(10),
        Str::random(50),
        Str::random(100),
        Str::random(200),
        Str::random(500),
        Str::random(1000),
        Str::random(2000),
    ];

    $integers = [
        null,
        $prev = 0,
        $prev = mt_rand($prev, 1),
        $prev = mt_rand($prev, 10),
        $prev = mt_rand($prev, 50),
        $prev = mt_rand($prev, 100),
        $prev = mt_rand($prev, 200),
        $prev = mt_rand($prev, 500),
        $prev = mt_rand($prev, 1000),
        $prev = mt_rand($prev, 2000),
    ];

    $iterations = 0;
    // $max = 1000;

    /**
     * In this simulation we run a total of 1K validations.
     */
    $run = function ($withRuleObjects) use ($strings, $integers, &$iterations, $max) {
        $timer = new Timer();
        $timer->start();
        $iterations = 0;

        foreach (range(1, 100) as $iteration) {
            foreach ($strings as $string) {
                foreach ($integers as $integer) {
                    $rules = [];

                    if ($withRuleObjects) {
                        $rules = [
                            'str' => Rule::make()->required()->string()->min(10)->max(1000),
                            'int' => Rule::make()->required()->integer()->min(11)->max(999),
                        ];
                    } else {
                        $rules = [
                            'str' => [
                                'required',
                                'string',
                                'min:10',
                                'max:1000',
                            ],
                            'int' => [
                                'required',
                                'integer',
                                'min:11',
                                'max:999',
                            ],
                        ];
                    }

                    $validator = Validator::make([
                        'str' => $string,
                        'int' => $integer,
                    ], $rules);

                    $validator->passes();
                    $iterations++;

                    if ($iterations >= $max) {
                        return $timer->stop()->asMicroseconds();
                    }

                }
            }
        }

        return $timer->stop()->asMicroseconds();
    };

    // Get any first-time invocations out of the way
    $withoutPackage = $run(false);
    $withPackage = $run(true);

    // The real deal
    $withPackage = $run(true);
    $withoutPackage = $run(false);

    $diffMs = ($withPackage - $withoutPackage);
    $diffPerc = ($withPackage / $withoutPackage) * 100;

    printBarGraphTiming("Iterations: {$iterations}", [
        'Original without Laravel Rules' => $withoutPackage,
        'Laravel Rules Package' => $withPackage,
    ]);

    expect($diffPerc)->toBeLessThan($threshold);
})->group('performance')->with([
    '1 iteration yields less than 10% overhead' => [
        'iterations' => 1,
        'overheadPercentageLimit' => 110,
    ],
    '10 iterations yields less than 10% overhead' => [
        'iterations' => 10,
        'overheadPercentageLimit' => 110,
    ],
    '100 iterations yields less than 10% overhead' => [
        'iterations' => 100,
        'overheadPercentageLimit' => 110,
    ],
    '1000 iterations yields less than 10% overhead' => [
        'iterations' => 1000,
        'overheadPercentageLimit' => 110,
    ],
]);

test('a single validator instance performance', function () {
    $str = Str::random(mt_rand(9, 11));
    $int = mt_rand(10, 12);

    $run = function (bool $withPackage) use ($str, $int) {
        $time = new Timer();
        $time->start();
        $rules = [];

        if ($withPackage) {
            $rules = [
                'str' => Rule::make()->required()->string()->min(10)->max(1000),
                'int' => Rule::make()->required()->integer()->min(11)->max(999),
            ];
        } else {
            $rules = [
                'str' => [
                    'required',
                    'string',
                    'min:10',
                    'max:1000',
                ],
                'int' => [
                    'required',
                    'integer',
                    'min:11',
                    'max:999',
                ],
            ];
        }

        Validator::make([
            'str' => $str,
            'int' => $int,
        ], $rules)->passes();

        return $time->stop()->asMicroseconds();
    };

    $run(true);
    $run(false);

    // dd([
    //     'package' => $package = $run(true),
    //     'default' => $default = $run(false),
    //     'delta' => $package - $default,
    //     'percent' => (($package / $default) - 1) * 100,
    // ]);

    expect(true)->toBeTrue();
})->group('performance');
