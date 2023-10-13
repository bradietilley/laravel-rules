<?php

use BradieTilley\Rules\Haltable\HaltableRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

test('a rule can fail early', function () {
    $rule = new class () implements ValidationRule {
        use HaltableRule;

        public function run(string $attribute, mixed $value): void
        {
            addHistory('failed');
            $this->failed('With Error Message');

            throw new \Exception('Well that did not work.');
        }
    };

    $validator = Validator::make([
        'field' => 'value',
    ], [
        'field' => [ $rule ],
    ]);

    expect($validator->passes())->toBe(false);
    expect($validator->errors()->toArray())->toBe([
        'field' => [
            'With Error Message',
        ],
    ]);
    expect(getHistory())->toBe(['failed']);
});
