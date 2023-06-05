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
     * @return $this
     */
    public function accepted(): self
    {
        return $this->rule('accepted');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-accepted-if
     * @return $this
     */
    public function acceptedIf(string ...$fieldsAndValues): self
    {
        return $this->rule('accepted_if'.self::arguments($fieldsAndValues));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-active-url
     * @return $this
     */
    public function activeUrl(): self
    {
        return $this->rule('active_url');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-after
     * @return $this
     */
    public function after(string|DateTimeInterface $date): self
    {
        return $this->rule('after'.self::arguments($date));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-after-or-equal
     * @return $this
     */
    public function afterOrEqual(string|DateTimeInterface $date): self
    {
        return $this->rule('after_or_equal'.self::arguments($date));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-alpha
     * @return $this
     */
    public function alpha(string $range = null): self
    {
        return $this->rule('alpha'.self::arguments($range));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-alpha-dash
     * @return $this
     */
    public function alphaDash(string $range = null): self
    {
        return $this->rule('alpha_dash'.self::arguments($range));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-alpha-numeric
     * @return $this
     */
    public function alphaNumeric(string $range = null): self
    {
        return $this->rule('alpha_numeric'.self::arguments($range));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-array
     * @return $this
     */
    public function array(string ...$keys): self
    {
        return $this->rule('array'.self::arguments($keys));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-ascii
     * @return $this
     */
    public function ascii(): self
    {
        return $this->rule('ascii');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-bail
     * @return $this
     */
    public function bail(): self
    {
        return $this->rule('bail');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-before
     * @return $this
     */
    public function before(string|DateTimeInterface $date): self
    {
        return $this->rule('before'.self::arguments($date));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-before-or-equal
     * @return $this
     */
    public function beforeOrEqual(string|DateTimeInterface $date): self
    {
        return $this->rule('before_or_equal'.self::arguments($date));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-between
     * @return $this
     */
    public function between(int $min, int $max): self
    {
        return $this->rule('between:'.$min.','.$max);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-boolean
     * @return $this
     */
    public function boolean(): self
    {
        return $this->rule('boolean');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-confirmed
     * @return $this
     */
    public function confirmed(): self
    {
        return $this->rule('confirmed');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-current-password
     * @return $this
     */
    public function currentPassword(string $guard = null): self
    {
        return $this->rule('current_password'.self::arguments($guard));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-date
     * @return $this
     */
    public function date(): self
    {
        return $this->rule('date');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-date-equals
     * @return $this
     */
    public function dateEquals(string|DateTimeInterface $date): self
    {
        return $this->rule('date_equals'.self::arguments($date));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-date-format
     * @return $this
     */
    public function dateFormat(string ...$format): self
    {
        return $this->rule('date_format'.self::arguments($format));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-decimal
     * @return $this
     */
    public function decimal(int $min, int $max = null): self
    {
        $max ??= $min;

        return $this->rule('decimal:'.$min.','.$max);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-declined
     * @return $this
     */
    public function declined(): self
    {
        return $this->rule('declined');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-declined-if
     * @return $this
     */
    public function declinedIf(string ...$fieldsAndValues): self
    {
        return $this->rule('declined_if'.self::arguments($fieldsAndValues));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-different
     * @return $this
     */
    public function different(string $field): self
    {
        return $this->rule('different:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-digits
     * @return $this
     */
    public function digits(int $value): self
    {
        return $this->rule('digits:'.$value);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-digits-between
     * @return $this
     */
    public function digitsBetween(int $min, int $max): self
    {
        return $this->rule('digits_between:'.$min.','.$max);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-dimensions
     * @return $this
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
    ): self {
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
     * @return $this
     */
    public function distinct(string $mode = null): self
    {
        return $this->rule('distinct'.self::arguments($mode));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-doesnt-start-with
     * @return $this
     */
    public function doesntStartWith(string ...$prefixes): self
    {
        return $this->rule('doesnt_start_with'.self::arguments($prefixes));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-doesnt-end-with
     * @return $this
     */
    public function doesntEndWith(string ...$prefixes): self
    {
        return $this->rule('doesnt_end_with'.self::arguments($prefixes));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-email
     * @return $this
     */
    public function email(string ...$flags): self
    {
        return $this->rule('email'.self::arguments($flags));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-ends-with
     * @return $this
     */
    public function endsWith(string ...$prefixes): self
    {
        return $this->rule('ends_with'.self::arguments($prefixes));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-enum
     * @return $this
     */
    public function enum(string|Enum $enum): self
    {
        return $this->rule($enum instanceof Enum ? $enum : RuleClass::enum($enum));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-exclude
     * @return $this
     */
    public function exclude(): self
    {
        return $this->rule('exclude');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-exclude-if
     * @return $this
     */
    public function excludeIf(callable|bool|ExcludeIf $condition): self
    {
        $excludeIf = $condition instanceof ExcludeIf ? $condition : RuleClass::excludeIf($condition);

        return $this->rule((string) $excludeIf);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-exclude-unless
     * @return $this
     */
    public function excludeUnless(string ...$fieldsAndValues): self
    {
        return $this->rule('exclude_unless'.self::arguments($fieldsAndValues));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-exclude-with
     * @return $this
     */
    public function excludeWith(string $field): self
    {
        return $this->rule('exclude_with:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-exclude-without
     * @return $this
     */
    public function excludeWithout(string $field): self
    {
        return $this->rule('exclude_without:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-exists
     * @return $this
     */
    public function exists(string $table, string $column = null): self
    {
        return $this->rule((string) RuleClass::exists($table, $column ?? 'NULL'));
    }

    /**
     * @param array<string> $allowedMimetypes
     * @param string|array<mixed> $customRules
     * @link https://laravel.com/docs/master/validation#rule-file
     * @return $this
     */
    public function file(
        ?File $file = null,
        array $allowedMimetypes = [],
        int $minKilobytes = null,
        int $maxKilobytes = null,
        string|array $customRules = [],
    ): self {
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
     * @return $this
     */
    public function filled(): self
    {
        return $this->rule('filled');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-greater-than
     * @return $this
     */
    public function gt(string $field): self
    {
        return $this->rule('gt:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-greater-than-or-equal
     * @return $this
     */
    public function gte(string $field): self
    {
        return $this->rule('gte:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-image
     * @return $this
     */
    public function image(ImageFile $image = null): self
    {
        return $this->rule($image ?? 'image');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-in
     * @return $this
     */
    public function in(string ...$values): self
    {
        return $this->rule('in'.self::arguments($values));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-in-array
     * @return $this
     */
    public function inArray(string $field): self
    {
        return $this->rule('in_array:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-integer
     * @return $this
     */
    public function integer(): self
    {
        return $this->rule('integer');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-ip
     * @return $this
     */
    public function ip(): self
    {
        return $this->rule('ip');
    }

    /**
     * @link https://laravel.com/docs/master/validation#ipv4
     * @return $this
     */
    public function ipv4(): self
    {
        return $this->rule('ipv4');
    }

    /**
     * @link https://laravel.com/docs/master/validation#ipv6
     * @return $this
     */
    public function ipv6(): self
    {
        return $this->rule('ipv6');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-json
     * @return $this
     */
    public function json(): self
    {
        return $this->rule('json');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-less-than
     * @return $this
     */
    public function lt(string $field): self
    {
        return $this->rule('lt:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-less-than-or-equal
     * @return $this
     */
    public function lte(string $field): self
    {
        return $this->rule('lte:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-lowercase
     * @return $this
     */
    public function lowercase(): self
    {
        return $this->rule('lowercase');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-mac-address
     * @return $this
     */
    public function macAddress(): self
    {
        return $this->rule('mac_address');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-max
     * @return $this
     */
    public function max(int $value): self
    {
        return $this->rule('max:'.$value);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-max-digits
     * @return $this
     */
    public function maxDigits(int $digits): self
    {
        return $this->rule('max_digits:'.$digits);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-mime-types
     * @return $this
     */
    public function mimeTypes(string ...$types): self
    {
        return $this->rule('mimetypes'.self::arguments($types));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-mime-type-by-file-extension
     * @return $this
     */
    public function mimes(string ...$extensions): self
    {
        return $this->rule('mimes'.self::arguments($extensions));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-min
     * @return $this
     */
    public function min(int $value): self
    {
        return $this->rule('min:'.$value);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-min-digits
     * @return $this
     */
    public function minDigits(int $value): self
    {
        return $this->rule('min_digits:'.$value);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-missing
     * @return $this
     */
    public function missing(): self
    {
        return $this->rule('missing');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-missing-if
     * @return $this
     */
    public function missingIf(string ...$fieldsAndValues): self
    {
        return $this->rule('missing_if'.self::arguments($fieldsAndValues));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-missing-unless
     * @return $this
     */
    public function missingUnless(string ...$fieldsAndValues): self
    {
        return $this->rule('missing_unless'.self::arguments($fieldsAndValues));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-missing-with
     * @return $this
     */
    public function missingWith(string ...$fields): self
    {
        return $this->rule('missing_with'.self::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-missing-with-all
     * @return $this
     */
    public function missingWithAll(string ...$fields): self
    {
        return $this->rule('missing_with_all'.self::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-multiple-of
     * @return $this
     */
    public function multipleOf(int $value): self
    {
        return $this->rule('multiple_of:'.$value);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-not-in
     * @return $this
     */
    public function notIn(string ...$values): self
    {
        return $this->rule('not_in'.self::arguments($values));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-not-regex
     * @return $this
     */
    public function notRegex(string $regex): self
    {
        return $this->rule('not_regex:'.$regex);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-nullable
     * @return $this
     */
    public function nullable(): self
    {
        return $this->rule('nullable');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-numeric
     * @return $this
     */
    public function numeric(): self
    {
        return $this->rule('numeric');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-password
     * @param string|array<mixed> $rules
     * @return $this
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
    ): self {
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
     * @return $this
     */
    public function present(): self
    {
        return $this->rule('present');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-prohibited
     * @return $this
     */
    public function prohibited(): self
    {
        return $this->rule('prohibited');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-prohibited-if
     * @return $this
     */
    public function prohibitedIf(
        string|bool|ProhibitedIf $condition = null,
        string ...$fieldsAndValues
    ): self {
        if (is_bool($condition)) {
            $condition = new ProhibitedIf($condition);
        }

        if ($condition instanceof ProhibitedIf) {
            return $this->rule((string) $condition);
        }

        if (is_string($condition)) {
            array_unshift($fieldsAndValues, $condition);
        }

        return $this->rule('prohibited_if'.self::arguments($fieldsAndValues));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-prohibited-unless
     * @return $this
     */
    public function prohibitedUnless(
        string|bool $condition = null,
        string ...$fieldsAndValues
    ): self {
        if (is_bool($condition)) {
            return $this->prohibitedIf(! $condition);
        }

        if (is_string($condition)) {
            array_unshift($fieldsAndValues, $condition);
        }

        return $this->rule('prohibited_unless'.self::arguments($fieldsAndValues));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-prohibits
     * @return $this
     */
    public function prohibits(string ...$fields): self
    {
        return $this->rule('prohibits'.self::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-regular-expression
     * @return $this
     */
    public function regex(string $pattern): self
    {
        return $this->rule('regex:'.$pattern);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required
     * @return $this
     */
    public function required(): self
    {
        return $this->rule('required');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required-if
     * @return $this
     */
    public function requiredIf(
        string|bool|RequiredIf|Closure $condition = null,
        string ...$fieldsAndValues
    ): self {
        if (is_bool($condition) || $condition instanceof Closure) {
            $condition = new RequiredIf($condition);
        }

        if ($condition instanceof RequiredIf) {
            return $this->rule((string) $condition);
        }

        if (is_string($condition)) {
            array_unshift($fieldsAndValues, $condition);
        }

        return $this->rule('required_if'.self::arguments($fieldsAndValues));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required-unless
     * @return $this
     */
    public function requiredUnless(
        string|bool|Closure $condition = null,
        string ...$fieldsAndValues
    ): self {
        if ($condition instanceof Closure) {
            $condition = !! $condition();
        }

        if (is_bool($condition)) {
            return $this->requiredIf(! $condition);
        }

        if (is_string($condition)) {
            array_unshift($fieldsAndValues, $condition);
        }

        return $this->rule('required_unless'.self::arguments($fieldsAndValues));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required-with
     * @return $this
     */
    public function requiredWith(string ...$fields): self
    {
        return $this->rule('required_with'.self::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required-with-all
     * @return $this
     */
    public function requiredWithAll(string ...$fields): self
    {
        return $this->rule('required_with_all'.self::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required-without
     * @return $this
     */
    public function requiredWithout(string ...$fields): self
    {
        return $this->rule('required_without'.self::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required-without-all
     * @return $this
     */
    public function requiredWithoutAll(string ...$fields): self
    {
        return $this->rule('required_without_all'.self::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required-array-keys
     * @return $this
     */
    public function requiredArrayKeys(string ...$keys): self
    {
        return $this->rule('required_array_keys'.self::arguments($keys));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-same
     * @return $this
     */
    public function same(string $field): self
    {
        return $this->rule('same:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-size
     * @return $this
     */
    public function size(string|int $value): self
    {
        return $this->rule('size:'.$value);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-sometimes
     * @return $this
     */
    public function sometimes(): self
    {
        return $this->rule('sometimes');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-starts-with
     * @return $this
     */
    public function startsWith(string ...$values): self
    {
        return $this->rule('starts_with'.self::arguments($values));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-string
     * @return $this
     */
    public function string(): self
    {
        return $this->rule('string');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-timezone
     * @return $this
     */
    public function timezone(?string $group = null, ?string $country = null): self
    {
        $arguments = [];

        if ($group !== null) {
            $arguments[] = $group;

            if ($country !== null) {
                $arguments[] = $country;
            }
        }

        return $this->rule('timezone'.self::arguments($arguments));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-unique
     * @return $this
     */
    public function unique(string|Unique $table, string $column = null, mixed $ignore = null): self
    {
        if (is_string($table)) {
            $table = (new Unique($table, $column ?? 'NULL'))->ignore($ignore);
        }

        return $this->rule((string) $table);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-uppercase
     * @return $this
     */
    public function uppercase(): self
    {
        return $this->rule('uppercase');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-url
     * @return $this
     */
    public function url(): self
    {
        return $this->rule('url');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-ulid
     * @return $this
     */
    public function ulid(): self
    {
        return $this->rule('ulid');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-uuid
     * @return $this
     */
    public function uuid(): self
    {
        return $this->rule('uuid');
    }
}
