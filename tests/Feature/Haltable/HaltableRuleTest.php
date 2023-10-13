<?php

use BradieTilley\Rules\Haltable\HaltableRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

test('a Haltable rule may specify a typed value argument', function () {
    $rule = new class () implements ValidationRule {
        use HaltableRule;

        public function run(string $attribute, string $value): void
        {
            addHistory('tested', $attribute, $value);
            $this->passed();
        }
    };

    $validator = Validator::make([
        'field' => 'value',
    ], [
        'field' => [ $rule ],
    ]);

    expect($validator->passes())->toBeTrue();
    expect(getHistory())->toBe(['tested', 'field', 'value']);

    clearHistory();

    $validator = Validator::make([
        'field' => [
            'an array',
        ],
    ], [
        'field' => [ $rule ],
    ]);

    expect(fn () => $validator->passes())->toThrow(TypeError::class);

    expect(getHistory())->toBeEmpty();
});

test('a Haltable rule may specify a custom catch callback', function () {
    $rule = new class () implements ValidationRule {
        use HaltableRule;

        public function run(string $attribute, string $value): void
        {
            $this->catch(function () {
                if (! in_array('first', getHistory())) {
                    addHistory('first');

                    throw new \InvalidArgumentException('Some invalid arg exception');
                }

                addHistory('second');
            });

            addHistory('passing');

            $this->passed();
        }
    };

    $validator = Validator::make([
        'field' => 'value',
    ], [
        'field' => [ $rule ],
    ]);

    expect($validator->passes())->toBeFalse();
    expect(getHistory())->toBe(['first']);

    expect($validator->passes())->toBeTrue();
    expect(getHistory())->toBe(['first', 'second', 'passing']);
});
