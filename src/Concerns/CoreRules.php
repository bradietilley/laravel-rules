<?php

namespace BradieTilley\Rules\Concerns;

use BradieTilley\Rules\Rule;
use Closure;
use DateTimeInterface;
use Illuminate\Validation\Rule as RuleClass;
use Illuminate\Validation\Rules\Dimensions;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\ExcludeIf;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\ProhibitedIf;
use Illuminate\Validation\Rules\RequiredIf;
use Illuminate\Validation\Rules\Unique;
use ReflectionProperty;

/**
 * @mixin Rule
 */
trait CoreRules
{
    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-accepted
     */
    public function accepted(): self
    {
        return $this->rule('accepted');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-accepted-if
     */
    public function acceptedIf(string ...$fieldsAndValues): self
    {
        return $this->rule('accepted_if'.self::arguments($fieldsAndValues));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-active-url
     */
    public function activeUrl(): self
    {
        return $this->rule('active_url');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-after
     */
    public function after(string|DateTimeInterface $date): self
    {
        return $this->rule('after'.self::arguments($date));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-after-or-equal
     */
    public function afterOrEqual(string|DateTimeInterface $date): self
    {
        return $this->rule('after_or_equal'.self::arguments($date));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-alpha
     */
    public function alpha(string $range = null): self
    {
        return $this->rule('alpha'.self::arguments($range));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-alpha-dash
     */
    public function alphaDash(string $range = null): self
    {
        return $this->rule('alpha_dash'.self::arguments($range));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-alpha-numeric
     */
    public function alphaNumeric(string $range = null): self
    {
        return $this->rule('alpha_numeric'.self::arguments($range));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-array
     */
    public function array(string ...$keys): self
    {
        return $this->rule('array'.self::arguments($keys));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-ascii
     */
    public function ascii(): self
    {
        return $this->rule('ascii');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-bail
     */
    public function bail(): self
    {
        if (func_num_args() === 1) {
            /** @phpstan-ignore-next-line */
            $this->field(func_get_arg(0));
        }

        return $this->rule('bail');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-before
     */
    public function before(string|DateTimeInterface $date): self
    {
        return $this->rule('before'.self::arguments($date));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-before-or-equal
     */
    public function beforeOrEqual(string|DateTimeInterface $date): self
    {
        return $this->rule('before_or_equal'.self::arguments($date));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-between
     */
    public function between(int $min, int $max): self
    {
        return $this->rule('between:'.$min.','.$max);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-boolean
     */
    public function boolean(): self
    {
        return $this->rule('boolean');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-confirmed
     */
    public function confirmed(): self
    {
        return $this->rule('confirmed');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-current-password
     */
    public function currentPassword(string $guard = null): self
    {
        return $this->rule('current_password'.self::arguments($guard));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-date
     */
    public function date(): self
    {
        return $this->rule('date');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-date-equals
     */
    public function dateEquals(string|DateTimeInterface $date): self
    {
        return $this->rule('date_equals'.self::arguments($date));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-date-format
     */
    public function dateFormat(string ...$format): self
    {
        return $this->rule('date_format'.self::arguments($format));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-decimal
     */
    public function decimal(int $min, int $max = null): self
    {
        $max ??= $min;

        return $this->rule('decimal:'.$min.','.$max);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-declined
     */
    public function declined(): self
    {
        return $this->rule('declined');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-declined-if
     */
    public function declinedIf(string ...$fieldsAndValues): self
    {
        return $this->rule('declined_if'.self::arguments($fieldsAndValues));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-different
     */
    public function different(string $field): self
    {
        return $this->rule('different:'.$field);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-digits
     */
    public function digits(int $value): self
    {
        return $this->rule('digits:'.$value);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-digits-between
     */
    public function digitsBetween(int $min, int $max): self
    {
        return $this->rule('digits_between:'.$min.','.$max);
    }

    /**
     * @return $this
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

        return $this->rule($dimensions);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-distinct
     */
    public function distinct(string $mode = null): self
    {
        return $this->rule('distinct'.self::arguments($mode));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-doesnt-start-with
     */
    public function doesntStartWith(string ...$prefixes): self
    {
        return $this->rule('doesnt_start_with'.self::arguments($prefixes));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-doesnt-end-with
     */
    public function doesntEndWith(string ...$prefixes): self
    {
        return $this->rule('doesnt_end_with'.self::arguments($prefixes));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-email
     */
    public function email(string ...$flags): self
    {
        return $this->rule('email'.self::arguments($flags));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-ends-with
     */
    public function endsWith(string ...$prefixes): self
    {
        return $this->rule('ends_with'.self::arguments($prefixes));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-enum
     */
    public function enum(string|Enum $enum): self
    {
        return $this->rule($enum instanceof Enum ? $enum : RuleClass::enum($enum));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-exclude
     */
    public function exclude(): self
    {
        if (func_num_args() === 1) {
            /** @phpstan-ignore-next-line */
            $this->field(func_get_arg(0));
        }

        return $this->rule('exclude');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-exclude-if
     */
    public function excludeIf(callable|bool|ExcludeIf $condition): self
    {
        return $this->rule($condition instanceof ExcludeIf ? $condition : RuleClass::excludeIf($condition));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-exclude-unless
     */
    public function excludeUnless(string ...$fieldsAndValues): self
    {
        return $this->rule('exclude_unless'.self::arguments($fieldsAndValues));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-exclude-with
     */
    public function excludeWith(string $field): self
    {
        return $this->rule('exclude_with:'.$field);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-exclude-without
     */
    public function excludeWithout(string $field): self
    {
        return $this->rule('exclude_without:'.$field);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-exists
     */
    public function exists(string $table, string $column = null): self
    {
        return $this->rule(RuleClass::exists($table, $column ?? 'NULL'));
    }

    /**
     * @param array<string> $allowedMimetypes
     * @param string|array<mixed> $customRules
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-file
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
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-filled
     */
    public function filled(): self
    {
        if (func_num_args() === 1) {
            /** @phpstan-ignore-next-line */
            $this->field(func_get_arg(0));
        }

        return $this->rule('filled');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-greater-than
     */
    public function gt(string $field): self
    {
        return $this->rule('gt:'.$field);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-greater-than-or-equal
     */
    public function gte(string $field): self
    {
        return $this->rule('gte:'.$field);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-image
     */
    public function image(): self
    {
        return $this->rule('image');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-in
     */
    public function in(string ...$values): self
    {
        return $this->rule('in'.self::arguments($values));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-in-array
     */
    public function inArray(string $field): self
    {
        return $this->rule('in_array:'.$field);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-integer
     */
    public function integer(): self
    {
        return $this->rule('integer');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-ip
     */
    public function ip(): self
    {
        return $this->rule('ip');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#ipv4
     */
    public function ipv4(): self
    {
        return $this->rule('ipv4');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#ipv6
     */
    public function ipv6(): self
    {
        return $this->rule('ipv6');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-json
     */
    public function json(): self
    {
        return $this->rule('json');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-less-than
     */
    public function lt(string $field): self
    {
        return $this->rule('lt:'.$field);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-less-than-or-equal
     */
    public function lte(string $field): self
    {
        return $this->rule('lte:'.$field);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-lowercase
     */
    public function lowercase(): self
    {
        return $this->rule('lowercase');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-mac-address
     */
    public function macAddress(): self
    {
        return $this->rule('mac_address');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-max
     */
    public function max(int $value): self
    {
        return $this->rule('max:'.$value);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-max-digits
     */
    public function maxDigits(int $digits): self
    {
        return $this->rule('max_digits:'.$digits);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-mime-types
     */
    public function mimeTypes(string ...$types): self
    {
        return $this->rule('mimetypes'.self::arguments($types));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-mime-type-by-file-extension
     */
    public function mimes(string ...$extensions): self
    {
        return $this->rule('mimes'.self::arguments($extensions));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-min
     */
    public function min(int $value): self
    {
        return $this->rule('min:'.$value);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-min-digits
     */
    public function minDigits(int $value): self
    {
        return $this->rule('min_digits:'.$value);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-missing
     */
    public function missing(): self
    {
        if (func_num_args() === 1) {
            /** @phpstan-ignore-next-line */
            $this->field(func_get_arg(0));
        }

        return $this->rule('missing');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-missing-if
     */
    public function missingIf(string ...$fieldsAndValues): self
    {
        return $this->rule('missing_if'.self::arguments($fieldsAndValues));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-missing-unless
     */
    public function missingUnless(string ...$fieldsAndValues): self
    {
        return $this->rule('missing_unless'.self::arguments($fieldsAndValues));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-missing-with
     */
    public function missingWith(string ...$fields): self
    {
        return $this->rule('missing_with'.self::arguments($fields));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-missing-with-all
     */
    public function missingWithAll(string ...$fields): self
    {
        return $this->rule('missing_with_all'.self::arguments($fields));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-multiple-of
     */
    public function multipleOf(int $value): self
    {
        return $this->rule('multiple_of:'.$value);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-not-in
     */
    public function notIn(string ...$values): self
    {
        return $this->rule('not_in'.self::arguments($values));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-not-regex
     */
    public function notRegex(string $regex): self
    {
        return $this->rule('not_regex:'.$regex);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-nullable
     */
    public function nullable(): self
    {
        if (func_num_args() === 1) {
            /** @phpstan-ignore-next-line */
            $this->field(func_get_arg(0));
        }

        return $this->rule('nullable');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-numeric
     */
    public function numeric(): self
    {
        return $this->rule('numeric');
    }

    /**
     * @param string|array<mixed> $rules
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-password
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
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-present
     */
    public function present(): self
    {
        return $this->rule('present');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-prohibited
     */
    public function prohibited(): self
    {
        return $this->rule('prohibited');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-prohibited-if
     */
    public function prohibitedIf(
        string|bool|ProhibitedIf $condition = null,
        string ...$fieldsAndValues
    ): self {
        if (is_bool($condition)) {
            $condition = new ProhibitedIf($condition);
        }

        if ($condition instanceof ProhibitedIf) {
            return $this->rule($condition);
        }

        if (is_string($condition)) {
            array_unshift($fieldsAndValues, $condition);
        }

        return $this->rule('prohibited_if'.self::arguments($fieldsAndValues));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-prohibited-unless
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
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-prohibits
     */
    public function prohibits(string ...$fields): self
    {
        return $this->rule('prohibits'.self::arguments($fields));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-regular-expression
     */
    public function regex(string $pattern): self
    {
        return $this->rule('regex:'.$pattern);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-required
     */
    public function required(): self
    {
        if (func_num_args() === 1) {
            /** @phpstan-ignore-next-line */
            $this->field(func_get_arg(0));
        }

        return $this->rule('required');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-required-if
     */
    public function requiredIf(
        string|bool|RequiredIf|Closure $condition = null,
        string ...$fieldsAndValues
    ): self {
        if (is_bool($condition) || $condition instanceof Closure) {
            $condition = new RequiredIf($condition);
        }

        if ($condition instanceof RequiredIf) {
            return $this->rule($condition);
        }

        if (is_string($condition)) {
            array_unshift($fieldsAndValues, $condition);
        }

        return $this->rule('required_if'.self::arguments($fieldsAndValues));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-required-unless
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
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-required-with
     */
    public function requiredWith(string ...$fields): self
    {
        return $this->rule('required_with'.self::arguments($fields));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-required-with-all
     */
    public function requiredWithAll(string ...$fields): self
    {
        return $this->rule('required_with_all'.self::arguments($fields));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-required-without
     */
    public function requiredWithout(string ...$fields): self
    {
        return $this->rule('required_without'.self::arguments($fields));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-required-without-all
     */
    public function requiredWithoutAll(string ...$fields): self
    {
        return $this->rule('required_without_all'.self::arguments($fields));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-required-array-keys
     */
    public function requiredArrayKeys(string ...$keys): self
    {
        return $this->rule('required_array_keys'.self::arguments($keys));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-same
     */
    public function same(string $field): self
    {
        return $this->rule('same:'.$field);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-size
     */
    public function size(string|int $value): self
    {
        return $this->rule('size:'.$value);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-sometimes
     */
    public function sometimes(): self
    {
        if (func_num_args() === 1) {
            /** @phpstan-ignore-next-line */
            $this->field(func_get_arg(0));
        }

        return $this->rule('sometimes');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-starts-with
     */
    public function startsWith(string ...$values): self
    {
        return $this->rule('starts_with'.self::arguments($values));
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-string
     */
    public function string(): self
    {
        return $this->rule('string');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-timezone
     *
     * @todo support new timezone rule config
     */
    public function timezone(): self
    {
        return $this->rule('timezone');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-unique
     */
    public function unique(string|Unique $table, string $column = null, mixed $ignore = null): self
    {
        if (is_string($table)) {
            $table = (new Unique($table, $column ?? 'NULL'))
                ->when($ignore !== null, fn (Unique $rule) => $rule->ignore($ignore));
        }

        return $this->rule($table);
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-uppercase
     */
    public function uppercase(): self
    {
        return $this->rule('uppercase');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-url
     */
    public function url(): self
    {
        return $this->rule('url');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-ulid
     */
    public function ulid(): self
    {
        return $this->rule('ulid');
    }

    /**
     * @return $this
     * @link https://laravel.com/docs/master/validation#rule-uuid
     */
    public function uuid(): self
    {
        return $this->rule('uuid');
    }
}
