<?php

declare(strict_types=1);

namespace BradieTilley\Rules\Concerns;

use BradieTilley\Rules\Rule;
use Closure;
use DateTimeInterface;
use Illuminate\Validation\Rule as RuleClass;
use Illuminate\Validation\Rules\Dimensions;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\ExcludeIf;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\ImageFile;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\ProhibitedIf;
use Illuminate\Validation\Rules\RequiredIf;
use Illuminate\Validation\Rules\Unique;
use ReflectionProperty;

/**
 * This trait represents every core validation rule found within
 * Laravel, and has been abstracted out of the parent Rule class
 * into a trait to maintain cleanliness.
 *
 * @mixin Rule
 * @link https://laravel.com/docs/master/validation#available-rules
 */
trait CoreRules
{
    /**
     * @link https://laravel.com/docs/master/validation#rule-accepted
     */
    public function accepted(): static
    {
        return $this->rule('accepted');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-accepted-if
     */
    public function acceptedIf(string|bool|Closure $field, string ...$values): static
    {
        if (!is_string($field)) {
            return $this->when($field, 'accepted');
        }

        return $this->rule('accepted_if'.static::arguments([
            $field,
            ...$values,
        ]));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-active-url
     */
    public function activeUrl(): static
    {
        return $this->rule('active_url');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-after
     */
    public function after(string|DateTimeInterface $date): static
    {
        return $this->rule('after'.static::arguments($date));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-after-or-equal
     */
    public function afterOrEqual(string|DateTimeInterface $date): static
    {
        return $this->rule('after_or_equal'.static::arguments($date));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-alpha
     */
    public function alpha(string $range = null): static
    {
        return $this->rule('alpha'.static::arguments($range));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-alpha-dash
     */
    public function alphaDash(string $range = null): static
    {
        return $this->rule('alpha_dash'.static::arguments($range));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-alpha-numeric
     */
    public function alphaNumeric(string $range = null): static
    {
        return $this->rule('alpha_numeric'.static::arguments($range));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-array
     */
    public function array(string ...$keys): static
    {
        return $this->rule('array'.static::arguments($keys));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-ascii
     */
    public function ascii(): static
    {
        return $this->rule('ascii');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-bail
     */
    public function bail(): static
    {
        return $this->rule('bail');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-before
     */
    public function before(string|DateTimeInterface $date): static
    {
        return $this->rule('before'.static::arguments($date));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-before-or-equal
     */
    public function beforeOrEqual(string|DateTimeInterface $date): static
    {
        return $this->rule('before_or_equal'.static::arguments($date));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-between
     */
    public function between(int $min, int $max): static
    {
        return $this->rule('between:'.$min.','.$max);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-boolean
     */
    public function boolean(): static
    {
        return $this->rule('boolean');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-confirmed
     */
    public function confirmed(): static
    {
        return $this->rule('confirmed');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-contains
     */
    public function contains(mixed ...$values): static
    {
        return $this->rule('contains'.static::arguments($values));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-current-password
     */
    public function currentPassword(string $guard = null): static
    {
        return $this->rule('current_password'.static::arguments($guard));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-date
     */
    public function date(): static
    {
        return $this->rule('date');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-date-equals
     */
    public function dateEquals(string|DateTimeInterface $date): static
    {
        return $this->rule('date_equals'.static::arguments($date));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-date-format
     */
    public function dateFormat(string ...$format): static
    {
        return $this->rule('date_format'.static::arguments($format));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-decimal
     */
    public function decimal(int $min, int $max = null): static
    {
        $max ??= $min;

        return $this->rule('decimal:'.$min.','.$max);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-declined
     */
    public function declined(): static
    {
        return $this->rule('declined');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-declined-if
     */
    public function declinedIf(string|bool|Closure $field, string ...$values): static
    {
        if (!is_string($field)) {
            return $this->when($field, 'declined');
        }

        return $this->rule('declined_if'.static::arguments([
            $field,
            ...$values,
        ]));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-different
     */
    public function different(string $field): static
    {
        return $this->rule('different:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-digits
     */
    public function digits(int $value): static
    {
        return $this->rule('digits:'.$value);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-digits-between
     */
    public function digitsBetween(int $min, int $max): static
    {
        return $this->rule('digits_between:'.$min.','.$max);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-dimensions
     */
    public function dimensions(
        string|Dimensions $dimensions = null,
        int $width = null,
        int $height = null,
        int $minWidth = null,
        int $minHeight = null,
        int $maxWidth = null,
        int $maxHeight = null,
        int $ratio = null,
    ): static {
        if ($dimensions === null) {
            $dimensions = RuleClass::dimensions();

            if ($width !== null) {
                $dimensions->width($width);
            }

            if ($height !== null) {
                $dimensions->height($height);
            }

            if ($minWidth !== null) {
                $dimensions->minWidth($minWidth);
            }

            if ($minHeight !== null) {
                $dimensions->minHeight($minHeight);
            }

            if ($maxWidth !== null) {
                $dimensions->maxWidth($maxWidth);
            }

            if ($maxHeight !== null) {
                $dimensions->maxHeight($maxHeight);
            }

            if ($ratio !== null) {
                $dimensions->ratio($ratio);
            }
        }

        return $this->rule((string) $dimensions);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-distinct
     */
    public function distinct(string $mode = null): static
    {
        return $this->rule('distinct'.static::arguments($mode));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-doesnt-start-with
     */
    public function doesntStartWith(string ...$prefixes): static
    {
        return $this->rule('doesnt_start_with'.static::arguments($prefixes));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-doesnt-end-with
     */
    public function doesntEndWith(string ...$prefixes): static
    {
        return $this->rule('doesnt_end_with'.static::arguments($prefixes));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-email
     */
    public function email(string ...$flags): static
    {
        return $this->rule('email'.static::arguments($flags));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-ends-with
     */
    public function endsWith(string ...$prefixes): static
    {
        return $this->rule('ends_with'.static::arguments($prefixes));
    }

    /**
     * @param class-string|Enum $enum
     *
     * @link https://laravel.com/docs/master/validation#rule-enum
     */
    public function enum(string|Enum $enum): static
    {
        return $this->rule($enum instanceof Enum ? $enum : RuleClass::enum($enum));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-exclude
     */
    public function exclude(): static
    {
        return $this->rule('exclude');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-exclude-if
     */
    public function excludeIf(callable|bool|ExcludeIf $condition): static
    {
        $excludeIf = $condition instanceof ExcludeIf ? $condition : RuleClass::excludeIf($condition);

        return $this->rule((string) $excludeIf);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-exclude-unless
     */
    public function excludeUnless(
        string|bool|Closure $field,
        string ...$values
    ): static {
        if (!is_string($field)) {
            return $this->unless($field, 'exclude');
        }

        return $this->rule('exclude_unless'.static::arguments([
            $field,
            ...$values,
        ]));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-exclude-with
     */
    public function excludeWith(string $field): static
    {
        return $this->rule('exclude_with:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-exclude-without
     */
    public function excludeWithout(string $field): static
    {
        return $this->rule('exclude_without:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-exists
     */
    public function exists(string|Exists $table, string $column = null): static
    {
        if (is_string($table)) {
            $table = RuleClass::exists($table, $column ?? 'NULL');
        }

        return $this->rule((string) $table);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-extensions
     */
    public function extensions(string ...$extensions): static
    {
        return $this->rule('extensions'.static::arguments($extensions));
    }

    /**
     * @param array<string> $allowedMimetypes
     * @param string|array<mixed> $customRules
     * @link https://laravel.com/docs/master/validation#rule-file
     */
    public function file(
        ?File $file = null,
        array $allowedMimetypes = [],
        int $minKilobytes = null,
        int $maxKilobytes = null,
        string|array $customRules = [],
    ): static {
        if ($file === null) {
            $file = RuleClass::file();

            if ($minKilobytes !== null) {
                $file->min($minKilobytes);
            }

            if ($maxKilobytes !== null) {
                $file->max($maxKilobytes);
            }

            if ($customRules !== null) {
                $file->rules($customRules);
            }

            if (!empty($allowedMimetypes)) {
                $reflection = new ReflectionProperty($file, 'allowedMimetypes');
                $reflection->setValue($file, $allowedMimetypes);
            }
        }

        return $this->rule($file);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-filled
     */
    public function filled(): static
    {
        return $this->rule('filled');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-greater-than
     */
    public function gt(string $field): static
    {
        return $this->rule('gt:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-greater-than-or-equal
     */
    public function gte(string $field): static
    {
        return $this->rule('gte:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-hex-color
     */
    public function hexColor(): static
    {
        return $this->rule('hex_color');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-image
     */
    public function image(ImageFile $image = null): static
    {
        return $this->rule($image ?? 'image');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-in
     */
    public function in(string ...$values): static
    {
        return $this->rule('in'.static::arguments($values));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-in-array
     */
    public function inArray(string $field): static
    {
        return $this->rule('in_array:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-integer
     */
    public function integer(): static
    {
        return $this->rule('integer');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-ip
     */
    public function ip(): static
    {
        return $this->rule('ip');
    }

    /**
     * @link https://laravel.com/docs/master/validation#ipv4
     */
    public function ipv4(): static
    {
        return $this->rule('ipv4');
    }

    /**
     * @link https://laravel.com/docs/master/validation#ipv6
     */
    public function ipv6(): static
    {
        return $this->rule('ipv6');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-json
     */
    public function json(): static
    {
        return $this->rule('json');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-less-than
     */
    public function lt(string $field): static
    {
        return $this->rule('lt:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-less-than-or-equal
     */
    public function lte(string $field): static
    {
        return $this->rule('lte:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-lowercase
     */
    public function lowercase(): static
    {
        return $this->rule('lowercase');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-list
     */
    public function list(): static
    {
        return $this->rule('list');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-mac-address
     */
    public function macAddress(): static
    {
        return $this->rule('mac_address');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-max
     */
    public function max(int $value): static
    {
        return $this->rule('max:'.$value);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-max-digits
     */
    public function maxDigits(int $digits): static
    {
        return $this->rule('max_digits:'.$digits);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-mime-types
     */
    public function mimeTypes(string ...$types): static
    {
        return $this->rule('mimetypes'.static::arguments($types));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-mime-type-by-file-extension
     */
    public function mimes(string ...$extensions): static
    {
        return $this->rule('mimes'.static::arguments($extensions));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-min
     */
    public function min(int $value): static
    {
        return $this->rule('min:'.$value);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-min-digits
     */
    public function minDigits(int $value): static
    {
        return $this->rule('min_digits:'.$value);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-missing
     */
    public function missing(): static
    {
        return $this->rule('missing');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-missing-if
     */
    public function missingIf(string|bool|Closure $field, string ...$values): static
    {
        if (!is_string($field)) {
            return $this->when($field, 'missing');
        }

        return $this->rule('missing_if'.static::arguments([
            $field,
            ...$values,
        ]));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-missing-unless
     */
    public function missingUnless(string|bool|Closure $field, string ...$values): static
    {
        if (!is_string($field)) {
            return $this->unless($field, 'missing');
        }

        return $this->rule('missing_unless'.static::arguments([
            $field,
            ...$values,
        ]));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-missing-with
     */
    public function missingWith(string ...$fields): static
    {
        return $this->rule('missing_with'.static::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-missing-with-all
     */
    public function missingWithAll(string ...$fields): static
    {
        return $this->rule('missing_with_all'.static::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-multiple-of
     */
    public function multipleOf(int $value): static
    {
        return $this->rule('multiple_of:'.$value);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-not-in
     */
    public function notIn(string ...$values): static
    {
        return $this->rule('not_in'.static::arguments($values));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-not-regex
     */
    public function notRegex(string $regex): static
    {
        return $this->rule('not_regex:'.$regex);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-nullable
     */
    public function nullable(): static
    {
        return $this->rule('nullable');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-numeric
     */
    public function numeric(): static
    {
        return $this->rule('numeric');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-password
     * @param string|array<mixed> $rules
     */
    public function password(
        Password $password = null,
        bool $letters = false,
        bool $mixedCase = false,
        bool $numbers = false,
        bool $symbols = false,
        int|bool $uncompromised = null,
        int $min = null,
        string|array $rules = [],
    ): static {
        if ($password === null) {
            $password = Password::default();

            /** use `if` conditions over `Conditionable` for lightweight invocation */
            if ($letters) {
                $password->letters();
            }

            if ($mixedCase) {
                $password->mixedCase();
            }

            if ($numbers) {
                $password->numbers();
            }

            if ($symbols) {
                $password->symbols();
            }

            if ($uncompromised !== null) {
                $password->uncompromised(is_int($uncompromised) ? $uncompromised : 0);
            }

            if ($min !== null) {
                $password->min($min);
            }

            if (! empty($rules)) {
                $password->rules($rules);
            }
        }

        return $this->rule($password);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-present
     */
    public function present(): static
    {
        return $this->rule('present');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-present-if
     */
    public function presentIf(string|bool|Closure $field, string ...$values): static
    {
        if (!is_string($field)) {
            return $this->when($field, 'present');
        }

        return $this->rule('present_if'.static::arguments([
            $field,
            ...$values,
        ]));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-present-unless
     */
    public function presentUnless(string|bool|Closure $field, string ...$values): static
    {
        if (!is_string($field)) {
            return $this->unless($field, 'present');
        }

        return $this->rule('present_unless'.static::arguments([
            $field,
            ...$values,
        ]));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-present-with
     */
    public function presentWith(string ...$fields): static
    {
        return $this->rule('present_with'.static::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-present-with-all
     */
    public function presentWithAll(string ...$fields): static
    {
        return $this->rule('present_with_all'.static::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-prohibited
     */
    public function prohibited(): static
    {
        return $this->rule('prohibited');
    }

    /**
     * If a `boolean` or `ProhibitedIf` or `Closure` is provided, it'll conditionally add
     * the `required` rule.
     *
     * @link https://laravel.com/docs/master/validation#rule-prohibited-if
     */
    public function prohibitedIf(string|bool|ProhibitedIf|Closure $field, string ...$values): static
    {
        if ($field instanceof ProhibitedIf) {
            return $this->rule((string) $field);
        }

        if (! is_string($field)) {
            return $this->when($field, 'prohibited');
        }

        return $this->rule('prohibited_if'.static::arguments([
            $field,
            ...$values,
        ]));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-prohibited-unless
     */
    public function prohibitedUnless(string|bool|Closure $field, string ...$values): static
    {
        if (! is_string($field)) {
            return $this->unless($field, 'prohibited');
        }

        return $this->rule('prohibited_unless'.static::arguments([
            $field,
            ...$values,
        ]));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-prohibits
     */
    public function prohibits(string ...$fields): static
    {
        return $this->rule('prohibits'.static::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-regular-expression
     */
    public function regex(string $pattern): static
    {
        return $this->rule('regex:'.$pattern);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required
     */
    public function required(): static
    {
        return $this->rule('required');
    }

    /**
     * If a `boolean` or `RequiredIf` or `Closure` is provided, it'll conditionally add
     * the `required` rule.
     *
     * @link https://laravel.com/docs/master/validation#rule-required-if
     */
    public function requiredIf(string|bool|RequiredIf|Closure $field, string ...$values): static
    {
        if ($field instanceof RequiredIf) {
            return $this->rule((string) $field);
        }

        if (! is_string($field)) {
            return $this->when($field, 'required');
        }

        return $this->rule('required_if'.static::arguments([
            $field,
            ...$values,
        ]));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required-if-accepted
     */
    public function requiredIfAccepted(string ...$fields): static
    {
        return $this->rule('required_if_accepted'.static::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required-if-declined
     */
    public function requiredIfDeclined(string ...$fields): static
    {
        return $this->rule('required_if_declined'.static::arguments($fields));
    }

    /**
     * If a `boolean` or `RequiredIf` or `Closure` is provided, it'll conditionally add
     * the `required` rule.
     *
     * @link https://laravel.com/docs/master/validation#rule-required-unless
     */
    public function requiredUnless(string|bool|Closure $field, string ...$values): static
    {
        if (! is_string($field)) {
            return $this->unless($field, 'required');
        }

        return $this->rule('required_unless'.static::arguments([
            $field,
            ...$values,
        ]));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required-with
     */
    public function requiredWith(string ...$fields): static
    {
        return $this->rule('required_with'.static::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required-with-all
     */
    public function requiredWithAll(string ...$fields): static
    {
        return $this->rule('required_with_all'.static::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required-without
     */
    public function requiredWithout(string ...$fields): static
    {
        return $this->rule('required_without'.static::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required-without-all
     */
    public function requiredWithoutAll(string ...$fields): static
    {
        return $this->rule('required_without_all'.static::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required-array-keys
     */
    public function requiredArrayKeys(string ...$keys): static
    {
        return $this->rule('required_array_keys'.static::arguments($keys));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-same
     */
    public function same(string $field): static
    {
        return $this->rule('same:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-size
     */
    public function size(string|int $value): static
    {
        return $this->rule('size:'.$value);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-sometimes
     */
    public function sometimes(): static
    {
        return $this->rule('sometimes');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-starts-with
     */
    public function startsWith(string ...$values): static
    {
        return $this->rule('starts_with'.static::arguments($values));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-string
     */
    public function string(): static
    {
        return $this->rule('string');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-timezone
     */
    public function timezone(?string $group = null, ?string $country = null): static
    {
        $arguments = array_filter([
            $group,
            $country,
        ]);

        return $this->rule('timezone'.static::arguments($arguments));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-unique
     */
    public function unique(string|Unique $table, string $column = null, mixed $ignore = null): static
    {
        if (is_string($table)) {
            $table = (new Unique($table, $column ?? 'NULL'))->ignore($ignore);
        }

        return $this->rule((string) $table);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-uppercase
     */
    public function uppercase(): static
    {
        return $this->rule('uppercase');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-url
     */
    public function url(): static
    {
        return $this->rule('url');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-ulid
     */
    public function ulid(): static
    {
        return $this->rule('ulid');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-uuid
     */
    public function uuid(): static
    {
        return $this->rule('uuid');
    }
}
