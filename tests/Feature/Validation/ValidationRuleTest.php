<?php

use BradieTilley\Rules\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class ValidationRuleHelper
{
    public static $history = [];
}

test('a ValidationRule rule', function () {
    ValidationRuleHelper::$history = [];

    $newRule = fn () => new class () extends ValidationRule {
        public function run(string $attribute, mixed $value): static
        {
            ValidationRuleHelper::$history[] = [$attribute, $value];

            if ($value === 123) {
                return $this->pass();
            }

            if ($value === 124) {
                return $this->fail('Test Message');
            }

            if ($value === 125) {
                return $this; // no result
            }

            return $this->pass();
        }
    };

    $validator = Validator::make([
        'field' => 123,
    ], [
        'field' => [ $newRule() ],
    ]);
    expect($validator->passes())->toBeTrue();
    expect(ValidationRuleHelper::$history)->toBe([['field', 123]]);

    ValidationRuleHelper::$history = [];

    $validator = Validator::make([
        'field' => 124,
    ], [
        'field' => [ $newRule() ],
    ]);
    expect($validator->passes())->toBeFalse();
    expect($validator->messages()->all())->toBe([
        'Test Message',
    ]);
    expect(ValidationRuleHelper::$history)->toBe([['field', 124]]);

    ValidationRuleHelper::$history = [];

    $validator = Validator::make([
        'field' => 125,
    ], [
        'field' => [ $newRule() ],
    ]);
    expect($validator->passes())->toBeFalse();
    expect($validator->messages()->all())->toBe([
        'No outcome was derived from rule class `BradieTilley\Rules\Validation\ValidationRule`',
    ]);
    expect(ValidationRuleHelper::$history)->toBe([['field', 125]]);
});
