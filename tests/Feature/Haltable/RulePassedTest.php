<?php

use BradieTilley\Rules\Haltable\HaltableRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

test('a rule can return early', function () {
    $rule = new class () implements ValidationRule {
        use HaltableRule;

        public function run(string $attribute, mixed $value): void
        {
            addHistory('passed');
            $this->passed();

            throw new \Exception('Well that did not work.');
        }
    };

    $validator = Validator::make([
        'field' => 'value',
    ], [
        'field' => [ $rule ],
    ]);

    expect($validator->passes())->toBeTrue();
    expect(getHistory())->toBe(['passed']);
});

test('a rule can still throw other exceptions', function () {
    $rule = new class () implements ValidationRule {
        use HaltableRule;

        public function run(string $attribute, mixed $value): void
        {
            throw new \InvalidArgumentException('Well that DID work.');
        }
    };

    $validator = Validator::make([
        'field' => 'value',
    ], [
        'field' => [ $rule ],
    ]);

    expect(fn () => $validator->passes())->toThrow(InvalidArgumentException::class, 'Well that DID work.');
});
