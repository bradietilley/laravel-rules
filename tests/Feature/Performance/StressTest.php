<?php

use BradieTilley\Rules\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use SebastianBergmann\Timer\Timer;

test('performance stress test', function () {
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

    /**
     * In this simulation we run a total of 1K validations.
     */
    $run = function ($withRuleObjects) use ($strings, $integers) {
        $timer = new Timer();
        $timer->start();

        foreach (range(1, 10) as $iteration) {
            foreach ($strings as $string) {
                foreach ($integers as $integer) {
                    $rules = [];

                    if ($withRuleObjects) {
                        $rules = Rule::fieldset(
                            Rule::make('str')->required()->string()->min(10)->max(1000),
                            Rule::make('int')->required()->integer()->min(11)->max(999),
                        );
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
                }
            }
        }

        return $timer->stop()->asMilliseconds();
    };

    $withPackage = $run(true);
    $withoutPackage = $run(false);

    $diffMs = ($withPackage - $withoutPackage);
    $diffPerc = ($withPackage / $withoutPackage) * 100;

    expect($diffPerc)->toBeLessThan(110);
});
