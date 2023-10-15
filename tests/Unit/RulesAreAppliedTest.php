<?php

declare(strict_types=1);

use BradieTilley\Rules\Rule;
use Carbon\Carbon;
use Illuminate\Validation\Rules\Dimensions;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\ExcludeIf;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\ImageFile;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\RequiredIf;
use Illuminate\Validation\Rules\Unique;
use Tests\Fixtures\AnExampleEnum;

it('applies the `accepted` rule', function () {
    $rule = Rule::make()->accepted();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'accepted',
        ]);
});

it('applies the `accepted_if` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->acceptedIf(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'one field/value' => [
        [ 'foo', 'bar', ],
        'accepted_if:foo,bar',
    ],
    'two fields/values' => [
        [ 'foo', 'bar', 'baz', 'biz', ],
        'accepted_if:foo,bar,baz,biz',
    ],
]);

it('applies the `active_url` rule', function () {
    $rule = Rule::make()->activeUrl();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'active_url',
        ]);
});

it('applies the `after` rule', function (string|Carbon $date, string $expect) {
    $rule = Rule::make()->after($date);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'field name' => [
        'another_field',
        'after:another_field',
    ],
    'DateTimeInterface object' => fn () => [
        Carbon::parse('2023-01-02 03:04:05'),
        'after:2023-01-02',
    ],
]);

it('applies the `after_or_equal` rule', function (string|Carbon $date, string $expect) {
    $rule = Rule::make()->afterOrEqual($date);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'field name' => [
        'another_field',
        'after_or_equal:another_field',
    ],
    'DateTimeInterface object' => fn () => [
        Carbon::parse('2023-01-02 03:04:05'),
        'after_or_equal:2023-01-02',
    ],
]);

it('applies the `alpha` rule', function (?string $range, string $expect) {
    $rule = Rule::make()->alpha($range);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'no range' => [
        null,
        'alpha',
    ],
    'ascii range' => [
        'ascii',
        'alpha:ascii',
    ],
]);

it('applies the `alpha_dash` rule', function (?string $range, string $expect) {
    $rule = Rule::make()->alphaDash($range);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'no range' => [
        null,
        'alpha_dash',
    ],
    'ascii range' => [
        'ascii',
        'alpha_dash:ascii',
    ],
]);

it('applies the `alpha_numeric` rule', function (?string $range, string $expect) {
    $rule = Rule::make()->alphaNumeric($range);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'no range' => [
        null,
        'alpha_numeric',
    ],
    'ascii range' => [
        'ascii',
        'alpha_numeric:ascii',
    ],
]);

it('applies the `array` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->array(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect
        ]);
})->with([
    'no keys' => [
        [],
        'array',
    ],
    'one key' => [
        [ 'foo' ],
        'array:foo',
    ],
    'two keys' => [
        [ 'foo', 'bar', ],
        'array:foo,bar',
    ],
]);

it('applies the `ascii` rule', function () {
    $rule = Rule::make()->ascii();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'ascii',
        ]);
});

it('applies the `bail` rule', function () {
    $rule = Rule::make()->bail();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'bail',
        ]);
});

it('applies the `before` rule', function (string|Carbon $date, string $expect) {
    $rule = Rule::make()->before($date);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'field name' => [
        'another_field',
        'before:another_field',
    ],
    'DateTimeInterface object' => fn () => [
        Carbon::parse('2023-01-02 03:04:05'),
        'before:2023-01-02',
    ],
]);

it('applies the `before_or_equal` rule', function (string|Carbon $date, string $expect) {
    $rule = Rule::make()->beforeOrEqual($date);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'field name' => [
        'another_field',
        'before_or_equal:another_field',
    ],
    'DateTimeInterface object' => fn () => [
        Carbon::parse('2023-01-02 03:04:05'),
        'before_or_equal:2023-01-02',
    ],
]);

it('applies the `between` rule', function () {
    $rule = Rule::make()->between(5, 28);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'between:5,28',
        ]);
});

it('applies the `boolean` rule', function () {
    $rule = Rule::make()->boolean();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'boolean',
        ]);
});

it('applies the `confirmed` rule', function () {
    $rule = Rule::make()->confirmed();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'confirmed',
        ]);
});

it('applies the `current_password` rule', function (?string $guard, string $expect) {
    $rule = Rule::make()->currentPassword($guard);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'no guard' => [
        null,
        'current_password',
    ],
    'with guard' => [
        'web',
        'current_password:web',
    ],
]);

it('applies the `date` rule', function () {
    $rule = Rule::make()->date();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'date',
        ]);
});

it('applies the `date_equals` rule', function (string|Carbon $date, string $expect) {
    $rule = Rule::make()->dateEquals($date);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'string' => [
        '2023-04-05',
        'date_equals:2023-04-05',
    ],
    'DateTimeInterface object' => fn () => [
        Carbon::parse('2023-05-07'),
        'date_equals:2023-05-07',
    ],
]);

it('applies the `date_format` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->dateFormat(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'one format' => [
        [ 'Y-m-d' ],
        'date_format:Y-m-d',
    ],
    'two formats' => [
        [ 'Y-m-d', 'Ymd' ],
        'date_format:Y-m-d,Ymd',
    ],
]);

it('applies the `decimal` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->decimal(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect
        ]);
})->with([
    'min only' => [
        [ 12 ],
        'decimal:12,12',
    ],
    'min + max' => [
        [ 12, 24 ],
        'decimal:12,24',
    ],
]);

it('applies the `declined` rule', function () {
    $rule = Rule::make()->declined();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'declined',
        ]);
});

it('applies the `declined_if` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->declinedIf(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'one field/value' => [
        [ 'foo', 'bar', ],
        'declined_if:foo,bar',
    ],
    'two fields/values' => [
        [ 'foo', 'bar', 'baz', 'biz', ],
        'declined_if:foo,bar,baz,biz',
    ],
]);

it('applies the `different` rule', function () {
    $rule = Rule::make()->different('another_field');
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'different:another_field',
        ]);
});

it('applies the `digits` rule', function () {
    $rule = Rule::make()->digits(10);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'digits:10',
        ]);
});

it('applies the `digits_between` rule', function () {
    $rule = Rule::make()->digitsBetween(2, 8);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'digits_between:2,8',
        ]);
});

it('applies the `dimensions` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->dimensions(...$arguments);

    expect($rule)->toBeInstanceOf(Rule::class);
    $rules = $rule->toArray();
    expect($rules)->toBeArray()->toHaveCount(1)->toHaveKey(0);

    expect((string) $rules[0])->toBe($expect);
})->with([
    'Dimensions object' => [
        [
            new Dimensions([
                'width' => 123,
                'height' => 456,
            ]),
        ],
        'dimensions:width=123,height=456'
    ],
    'constraint parameters' => [
        [
            null,
            11,
            22,
            33,
            44,
            55,
            66,
            77,
        ],
        'dimensions:width=11,height=22,min_width=33,min_height=44,max_width=55,max_height=66,ratio=77'
    ],
]);

it('applies the `distinct` rule', function (?string $mode, string $expect) {
    $rule = Rule::make()->distinct($mode);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'no mode' => [
        null,
        'distinct',
    ],
    'strict mode' => [
        'strict',
        'distinct:strict',
    ],
    'ignore_case mode' => [
        'ignore_case',
        'distinct:ignore_case',
    ],
]);

it('applies the `doesnt_start_with` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->doesntStartWith(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'one key' => [
        [ 'foo', ],
        'doesnt_start_with:foo',
    ],
    'two keys' => [
        [ 'foo', 'bar', ],
        'doesnt_start_with:foo,bar',
    ],
]);

it('applies the `doesnt_end_with` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->doesntEndWith(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'one key' => [
        [ 'foo', ],
        'doesnt_end_with:foo',
    ],
    'two keys' => [
        [ 'foo', 'bar', ],
        'doesnt_end_with:foo,bar',
    ],
]);

it('applies the `email` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->email(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'no arguments' => [
        [],
        'email',
    ],
    'one argument' => [
        [ 'rfc' ],
        'email:rfc',
    ],
    'all arguments' => [
        [ 'rfc', 'strict', 'dns', 'spoof', 'filter', 'filter_unicode' ],
        'email:rfc,strict,dns,spoof,filter,filter_unicode',
    ],
]);

it('applies the `ends_with` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->endsWith(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'one argument' => [
        [ 'foo' ],
        'ends_with:foo',
    ],
    'two arguments' => [
        [ 'foo', 'bar' ],
        'ends_with:foo,bar',
    ],
]);

it('applies the `enum` rule', function (string|Enum $enum, string $expect) {
    $rule = Rule::make()->enum($enum);

    expect($rule)->toBeInstanceOf(Rule::class);
    $rules = $rule->toArray();
    expect($rules)->toBeArray()->toHaveCount(1)->toHaveKey(0);

    $actual = (new ReflectionProperty($rules[0], 'type'))->getValue($rules[0]);
    expect($actual)->toBe($expect);
})->with([
    'class string' => [
        AnExampleEnum::class,
        AnExampleEnum::class,
    ],
    'Enum objct' => fn () => [
        new Enum(AnExampleEnum::class),
        AnExampleEnum::class,
    ],
]);

it('applies the `exclude` rule', function () {
    $rule = Rule::make()->exclude();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'exclude',
        ]);
});

it('applies the `exclude_if` rule', function (bool|Closure|ExcludeIf $condition, string $expect) {
    $rule = Rule::make()->excludeIf($condition);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'false condition' => fn () => [
        false,
        ''
    ],
    'true condition' => fn () => [
        true,
        'exclude'
    ],
    'false callback' => fn () => [
        fn () => false,
        ''
    ],
    'true callback' => fn () => [
        fn () => true,
        'exclude'
    ],
]);

it('applies the `exclude_unless` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->excludeUnless(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'boolean = true' => [
        [ true ],
        '',
    ],
    'boolean = false' => [
        [ false ],
        'exclude',
    ],
    'closure = true' => [
        [ fn () => true ],
        '',
    ],
    'closure = false' => [
        [ fn () => false ],
        'exclude',
    ],
    'single field/value' => [
        [ 'foo', 'bar', ],
        'exclude_unless:foo,bar',
    ],
    'multiple fields/values' => [
        [ 'foo', 'bar', 'baz', 'biz', ],
        'exclude_unless:foo,bar,baz,biz',
    ],
]);

it('applies the `exclude_with` rule', function () {
    $rule = Rule::make()->excludeWith('another_field');
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'exclude_with:another_field',
        ]);
});

it('applies the `exclude_without` rule', function () {
    $rule = Rule::make()->excludeWithout('example_field');
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'exclude_without:example_field',
        ]);
});

it('applies the `exists` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->exists(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'table only' => [
        [ 'products' ],
        'exists:products,NULL',
    ],
    'table and column' => [
        [ 'users', 'email', ],
        'exists:users,email',
    ],
    'exists object' => fn () => [
        [ $object = (new Exists('settings', 'id'))->withoutTrashed(), ],
        (string) $object,
    ],
]);

it('applies the `file` rule', function (array $arguments, array $expect) {
    $rule = Rule::make()->file(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toHaveCount(1);

    $file = $rule->toArray()[0];
    $properties = getPropertiesFromObject($file, [
        'allowedMimetypes',
        'minimumFileSize',
        'maximumFileSize',
        'customRules',
    ]);

    expect($properties)->toBe($expect);
})->with([
    'allowed mimetypes only' => [
        [ null, ['image/png', 'image/jpeg'], ],
        [ 'allowedMimetypes' => ['image/png', 'image/jpeg'], 'minimumFileSize' => null, 'maximumFileSize' => null, 'customRules' => [], ],
    ],
    'all file arguments' => [
        [ null, ['image/webp', 'image/jpeg'], 10, 100, [ 'ABC',] ],
        [ 'allowedMimetypes' => ['image/webp', 'image/jpeg'], 'minimumFileSize' => 10, 'maximumFileSize' => 100, 'customRules' => [ 'ABC', ] ],
    ],
    'file argument' => [
        [ File::types(['application/pdf'])->min(43)->max(74), ],
        [ 'allowedMimetypes' => ['application/pdf'], 'minimumFileSize' => 43, 'maximumFileSize' => 74, 'customRules' => [] ],
    ],
]);

it('applies the `filled` rule', function () {
    $rule = Rule::make()->filled();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'filled',
        ]);
});

it('applies the `gt` rule', function () {
    $rule = Rule::make()->gt('another_field');
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'gt:another_field',
        ]);
});

it('applies the `gte` rule', function () {
    $rule = Rule::make()->gte('another_field');
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'gte:another_field',
        ]);
});

it('applies the `image` rule', function (?ImageFile $image, string|ImageFile $expect) {
    $rule = Rule::make()->image($image);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'no argument' => [
        null,
        'image',
    ],
    'image object' => fn () => [
        $image = File::image(),
        $image,
    ],
]);

it('applies the `in` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->in(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'one value' => [
        [ 'foo', ],
        'in:foo',
    ],
    'two values' => [
        [ 'foo', 'bar', ],
        'in:foo,bar',
    ],
]);

it('applies the `in_array` rule', function () {
    $rule = Rule::make()->inArray('foo');
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'in_array:foo',
        ]);
});

it('applies the `integer` rule', function () {
    $rule = Rule::make()->integer();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'integer',
        ]);
});

it('applies the `ip` rule', function () {
    $rule = Rule::make()->ip();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'ip',
        ]);
});

it('applies the `ipv4` rule', function () {
    $rule = Rule::make()->ipv4();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'ipv4',
        ]);
});

it('applies the `ipv6` rule', function () {
    $rule = Rule::make()->ipv6();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'ipv6',
        ]);
});

it('applies the `json` rule', function () {
    $rule = Rule::make()->json();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'json',
        ]);
});

it('applies the `lt` rule', function () {
    $rule = Rule::make()->lt('another_field');
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'lt:another_field',
        ]);
});

it('applies the `lte` rule', function () {
    $rule = Rule::make()->lte('another_field');
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'lte:another_field',
        ]);
});

it('applies the `lowercase` rule', function () {
    $rule = Rule::make()->lowercase();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'lowercase',
        ]);
});

it('applies the `mac_address` rule', function () {
    $rule = Rule::make()->macAddress();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'mac_address',
        ]);
});

it('applies the `max` rule', function () {
    $rule = Rule::make()->max(128);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'max:128',
        ]);
});

it('applies the `max_digits` rule', function () {
    $rule = Rule::make()->maxDigits(9);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'max_digits:9',
        ]);
});

it('applies the `mime_types` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->mimeTypes(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'single mime type' => [
        [ 'image/jpeg' ],
        'mimetypes:image/jpeg'
    ],
    'mulitple mime types' => [
        [ 'image/jpeg', 'image/png' ],
        'mimetypes:image/jpeg,image/png'
    ]
]);

it('applies the `mimes` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->mimes(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'single mime type' => [
        [ 'jpeg' ],
        'mimes:jpeg'
    ],
    'mulitple mime types' => [
        [ 'jpeg', 'png' ],
        'mimes:jpeg,png'
    ]
]);

it('applies the `min` rule', function () {
    $rule = Rule::make()->min(345);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'min:345',
        ]);
});

it('applies the `min_digits` rule', function () {
    $rule = Rule::make()->minDigits(6);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'min_digits:6',
        ]);
});

it('applies the `missing` rule', function () {
    $rule = Rule::make()->missing();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'missing',
        ]);
});

it('applies the `missing_if` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->missingIf(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'one field/value' => [
        [ 'foo', 'bar', ],
        'missing_if:foo,bar',
    ],
    'two fields/values' => [
        [ 'foo', 'bar', 'baz', 'biz', ],
        'missing_if:foo,bar,baz,biz',
    ],
]);

it('applies the `missing_unless` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->missingUnless(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'one field/value' => [
        [ 'foo', 'bar', ],
        'missing_unless:foo,bar',
    ],
    'two fields/values' => [
        [ 'foo', 'bar', 'baz', 'biz', ],
        'missing_unless:foo,bar,baz,biz',
    ],
]);

it('applies the `missing_with` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->missingWith(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'one argument' => [
        [ 'foo', ],
        'missing_with:foo',
    ],
    'two arguments' => [
        [ 'foo', 'bar', ],
        'missing_with:foo,bar',
    ],
]);

it('applies the `missing_with_all` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->missingWithAll(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'one argument' => [
        [ 'foo', ],
        'missing_with_all:foo',
    ],
    'two arguments' => [
        [ 'foo', 'bar', ],
        'missing_with_all:foo,bar',
    ],
]);

it('applies the `multiple_of` rule', function () {
    $rule = Rule::make()->multipleOf(12);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'multiple_of:12',
        ]);
});

it('applies the `not_in` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->notIn(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'one argument' => [
        [ 'foo', ],
        'not_in:foo',
    ],
    'two arguments' => [
        [ 'foo', 'bar', ],
        'not_in:foo,bar',
    ],
]);

it('applies the `not_regex` rule', function () {
    $rule = Rule::make()->notRegex('/^[abc]$/');
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'not_regex:/^[abc]$/',
        ]);
});

it('applies the `nullable` rule', function () {
    $rule = Rule::make()->nullable();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'nullable',
        ]);
});

it('applies the `numeric` rule', function () {
    $rule = Rule::make()->numeric();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'numeric',
        ]);
});

it('applies the `password` rule', function (array $arguments, array $expect) {
    $rule = Rule::make()->password(...$arguments);

    expect($rule)->toBeInstanceOf(Rule::class);
    $rules = $rule->toArray();
    expect($rules)->toBeArray()->toHaveCount(1)->toHaveKey(0);

    $password = $rules[0];
    expect($password)->toBeInstanceOf(Password::class);

    $properties = getPropertiesFromObject($password, [
        'min',
        'mixedCase',
        'letters',
        'numbers',
        'symbols',
        'uncompromised',
        'compromisedThreshold',
        'customRules',
    ]);
    expect($properties)->toBe($expect);
})->with([
    'Password object' => fn () => [
        [
            Password::min(8),
        ],
        [
            'min' => 8,
            'mixedCase' => false,
            'letters' => false,
            'numbers' => false,
            'symbols' => false,
            'uncompromised' => false,
            'compromisedThreshold' => 0,
            'customRules' => [],
        ],
    ],
    'letters parameter' => fn () => [
        [
            null, // password object
            $letters = true,
        ],
        [
            'min' => 8,
            'mixedCase' => false,
            'letters' => true,
            'numbers' => false,
            'symbols' => false,
            'uncompromised' => false,
            'compromisedThreshold' => 0,
            'customRules' => [],
        ],
    ],
    'mixedCase parameter' => fn () => [
        [
            null, // password object
            $letters = false,
            $mixedCase = true,
        ],
        [
            'min' => 8,
            'mixedCase' => true,
            'letters' => false,
            'numbers' => false,
            'symbols' => false,
            'uncompromised' => false,
            'compromisedThreshold' => 0,
            'customRules' => [],
        ],
    ],
    'numbers parameter' => fn () => [
        [
            null, // password object
            $letters = false,
            $mixedCase = false,
            $numbers = true,
        ],
        [
            'min' => 8,
            'mixedCase' => false,
            'letters' => false,
            'numbers' => true,
            'symbols' => false,
            'uncompromised' => false,
            'compromisedThreshold' => 0,
            'customRules' => [],
        ],
    ],
    // 'constraint parameters' => fn () => [
    //     [
    //         null, // password object
    //         $letters = bool_rand(),
    //         $mixedCase = bool_rand(),
    //         $numbers = bool_rand(),
    //         $symbols = bool_rand(),
    //         $uncompromised = bool_rand() ? bool_rand() : random_int(0, 9),
    //         $min = (bool_rand() ? null : random_int(0, 20)),
    //         $rules = [], // meh
    //     ],
    //     [
    //         'min' => $min ?? 8,
    //         'mixedCase' => $mixedCase,
    //         'letters' => $letters,
    //         'numbers' => $numbers,
    //         'symbols' => $symbols,
    //         'uncompromised' => ($uncompromised === true),
    //         'compromisedThreshold' => is_int($uncompromised) ? $uncompromised : 0,
    //         'customRules' => $rules,
    //     ],
    // ],
]);

it('applies the `present` rule', function () {
    $rule = Rule::make()->present();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'present',
        ]);
});

it('applies the `prohibited` rule', function () {
    $rule = Rule::make()->prohibited();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'prohibited',
        ]);
});

it('applies the `prohibited_if` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->prohibitedIf(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'one field/value' => [
        [ 'foo', 'bar', ],
        'prohibited_if:foo,bar',
    ],
    'two fields/values' => [
        [ 'foo', 'bar', 'baz', 'biz', ],
        'prohibited_if:foo,bar,baz,biz',
    ],
]);

it('applies the `prohibited_unless` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->prohibitedUnless(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'one field/value' => [
        [ 'foo', 'bar', ],
        'prohibited_unless:foo,bar',
    ],
    'two fields/values' => [
        [ 'foo', 'bar', 'baz', 'biz', ],
        'prohibited_unless:foo,bar,baz,biz',
    ],
]);

it('applies the `prohibits` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->prohibits(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'one argument' => [
        [ 'foo', ],
        'prohibits:foo',
    ],
    'two arguments' => [
        [ 'foo', 'bar', ],
        'prohibits:foo,bar',
    ],
]);

it('applies the `regex` rule', function () {
    $rule = Rule::make()->regex('/^[abc]$/');
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'regex:/^[abc]$/',
        ]);
});

it('applies the `required` rule', function () {
    $rule = Rule::make()->required();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'required',
        ]);
});

it('applies the `required_if` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->requiredIf(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'boolean = true' => [
        [ true ],
        'required',
    ],
    'boolean = false' => [
        [ false ],
        '',
    ],
    'closure = true' => [
        [ fn () => true ],
        'required',
    ],
    'closure = false' => [
        [ fn () => false ],
        '',
    ],
    'RequiredIf object bool = true' => [
        [ new RequiredIf(true) ],
        'required',
    ],
    'RequiredIf object bool = false' => [
        [ new RequiredIf(false) ],
        '',
    ],
    'RequiredIf object closure = true' => [
        [ new RequiredIf(fn () => true) ],
        'required',
    ],
    'RequiredIf object closure = false' => [
        [ new RequiredIf(fn () => false) ],
        '',
    ],
    'single field/value' => [
        [ 'foo', 'bar', ],
        'required_if:foo,bar',
    ],
    'multiple fields/values' => [
        [ 'foo', 'bar', 'baz', 'biz', ],
        'required_if:foo,bar,baz,biz',
    ],
]);

it('applies the `required_unless` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->requiredUnless(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'boolean = true' => [
        [ true ],
        '',
    ],
    'boolean = false' => [
        [ false ],
        'required',
    ],
    'closure = true' => [
        [ fn () => true ],
        '',
    ],
    'closure = false' => [
        [ fn () => false ],
        'required',
    ],
    'single field/value' => [
        [ 'foo', 'bar', ],
        'required_unless:foo,bar',
    ],
    'multiple fields/values' => [
        [ 'foo', 'bar', 'baz', 'biz', ],
        'required_unless:foo,bar,baz,biz',
    ],
]);

it('applies the `required_with` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->requiredWith(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'single field/value' => [
        [ 'foo', 'bar', ],
        'required_with:foo,bar',
    ],
    'multiple fields/values' => [
        [ 'foo', 'bar', 'baz', 'biz', ],
        'required_with:foo,bar,baz,biz',
    ],
]);

it('applies the `required_with_all` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->requiredWithAll(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'single field/value' => [
        [ 'foo', 'bar', ],
        'required_with_all:foo,bar',
    ],
    'multiple fields/values' => [
        [ 'foo', 'bar', 'baz', 'biz', ],
        'required_with_all:foo,bar,baz,biz',
    ],
]);

it('applies the `required_without` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->requiredWithout(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'single field/value' => [
        [ 'foo', 'bar', ],
        'required_without:foo,bar',
    ],
    'multiple fields/values' => [
        [ 'foo', 'bar', 'baz', 'biz', ],
        'required_without:foo,bar,baz,biz',
    ],
]);

it('applies the `required_without_all` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->requiredWithoutAll(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'single field/value' => [
        [ 'foo', 'bar', ],
        'required_without_all:foo,bar',
    ],
    'multiple fields/values' => [
        [ 'foo', 'bar', 'baz', 'biz', ],
        'required_without_all:foo,bar,baz,biz',
    ],
]);

it('applies the `required_array_keys` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->requiredArrayKeys(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'one key' => [
        [ 'foo' ],
        'required_array_keys:foo',
    ],
    'two keys' => [
        [ 'foo', 'bar', ],
        'required_array_keys:foo,bar',
    ],
]);

it('applies the `same` rule', function () {
    $rule = Rule::make()->same('another_field');
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'same:another_field',
        ]);
});

it('applies the `size` rule', function (string|int $value, string $expect) {
    $rule = Rule::make()->size($value);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'field reference' => [
        'another_field',
        'size:another_field',
    ],
    'numeric value' => [
        8,
        'size:8',
    ],
]);

it('applies the `sometimes` rule', function () {
    $rule = Rule::make()->sometimes();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'sometimes',
        ]);
});

it('applies the `starts_with` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->startsWith(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'single argument' => [
        [ 'foo' ],
        'starts_with:foo'
    ],
    'mulitple argument' => [
        [ 'foo', 'bar' ],
        'starts_with:foo,bar'
    ]
]);

it('applies the `string` rule', function () {
    $rule = Rule::make()->string();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'string',
        ]);
});

it('applies the `timezone` rule', function () {
    $rule = Rule::make()->timezone();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'timezone',
        ]);
})->with([
    'no arguments' => [
        [],
        'timezone',
    ],
    'group argument' => [
        [ 'PACIFIC' ],
        'timezone:PACIFIC',
    ],
    'group + country arguments' => [
        [ 'PER_COUNTRY', 'NZ', ],
        'timezone:PACIFIC,NZ',
    ],
]);

it('applies the `unique` rule', function (array $arguments, string $expect) {
    $rule = Rule::make()->unique(...$arguments);
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            $expect,
        ]);
})->with([
    'Unique object' => [
        [ new Unique('users', 'email'), ],
        'unique:users,email,NULL,id',
    ],
    'table parameter' => [
        [
            'users'
        ],
        'unique:users,NULL,NULL,id',
    ],
    'table+column parameters' => [
        [
            'users',
            'email',
        ],
        'unique:users,email,NULL,id',
    ],
    'table+column+ignore parameters' => [
        [
            'users',
            'email',
            '123',
        ],
        'unique:users,email,"123",id',
    ],
]);

it('applies the `uppercase` rule', function () {
    $rule = Rule::make()->uppercase();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'uppercase',
        ]);
});

it('applies the `url` rule', function () {
    $rule = Rule::make()->url();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'url',
        ]);
});

it('applies the `ulid` rule', function () {
    $rule = Rule::make()->ulid();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'ulid',
        ]);
});

it('applies the `uuid` rule', function () {
    $rule = Rule::make()->uuid();
    expect($rule)
        ->toBeInstanceOf(Rule::class)
        ->toArray()
        ->toBe([
            'uuid',
        ]);
});
