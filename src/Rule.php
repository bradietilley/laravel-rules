<?php

namespace BradieTilley\Rules;

use DateTimeInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Validation\Rule as RuleContract;
use Illuminate\Contracts\Validation\ValidationRule as ValidationRuleContract;
use Illuminate\Validation\Rule as RuleClass;
use Illuminate\Validation\Rules\Dimensions;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\ProhibitedIf;
use Illuminate\Validation\Rules\RequiredIf;
use Illuminate\Validation\Rules\Unique;
use Iterator;
use ReflectionProperty;

class Rule implements Iterator, Arrayable
{
    /**
     * @var array<string|ValidationRuleContract|RuleContract>
     */
    protected array $rules = [];

    private int $iterator = 0;

    /**
     * @var class-string
     */
    protected static string $using = Rule::class;

    public function __construct(protected ?string $field = null)
    {
    }

    /**
     * @param class-string $class
     */
    public static function using(string $class): void
    {
        self::$using = $class;
    }

    public static function usingDefault(): void
    {
        self::$using = Rule::class;
    }

    /**
     * Make a new instance
     */
    public static function make(?string $field = null): self
    {
        $class = self::$using;
        $instance = new $class($field);

        assert($instance instanceof Rule);

        return $instance;
    }

    /**
     * ===============================================================
     * ======== Internal / Interfaceable functions
     * ===============================================================
     */

    /**
     * Make a set of rules keyed by each rule's field.
     *
     * @return array<string, Rule>
     */
    public static function fieldset(Rule ...$rules): array
    {
        $keyed = [];

        foreach ($rules as $rule) {
            $keyed[$rule->requireFieldName()] = $rule;
        }

        return $keyed;
    }

    /**
     * Set the field name for this Rule.
     *
     * This is only required if you're building a ruleset using
     * the `Rule::fields()` method or if you're planning on
     * running assertions against which fields have what rules.
     */
    public function field(?string $field = null): self|string|null
    {
        if ($field !== null) {
            $this->field = $field;
        }

        return $this->field;
    }

    /**
     * Get the field name and abort if name not supplied
     */
    public function requireFieldName(): string
    {
        $field = $this->field;

        if ($field === null) {
            throw new \Exception('Rule must have a field in order to reference rule field');
        }

        return $field;
    }

    /**
     * Add a rule
     */
    protected function rule(string|ValidationRuleContract|RuleContract|null $rule): self
    {
        if ($rule === null) {
            return $this;
        }

        $this->rules[] = $rule;

        return $this;
    }

    /**
     * Standardise the value for string concatenation.
     */
    protected static function standardise(int|string|DateTimeInterface $date): string
    {
        if ($date instanceof DateTimeInterface) {
            return $date->format('Y-m-d');
        }

        return (string) $date;
    }

    /**
     * Prepare the value for string concatenation. When the value is not null, the given prefix
     * will be applied.
     *
     * @param array<int, int|string|DateTimeInterface>|int|string|DateTimeInterface|null $value
     * @phpstan-ignore-next-line - Method BradieTilley\Rules\Rule::arguments() has parameter $value with no value type specified in iterable type array.
     */
    protected static function arguments(array|string|DateTimeInterface|null $value, string $prefix = ':'): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        if (is_array($value)) {
            $value = array_map(self::standardise(...), $value);
            $value = implode(',', $value);
        } else {
            $value = self::standardise($value);
        }

        return ($value !== null && $value !== '') ? ($prefix.$value) : '';
    }

    /**
     * ===============================================================
     * ======== Iterator functions
     * ===============================================================
     */

    /**
     * Iterator Interface: Get the iterator's current value
     */
    public function current(): mixed
    {
        return $this->rules[$this->iterator];
    }

    /**
     * Iterator Interface: Set the next iterator index
     */
    public function next(): void
    {
        $this->iterator++;
    }

    /**
     * Iterator Interface: Set the previous iterator index
     */
    public function prev(): void
    {
        $this->iterator--;
    }

    /**
     * Iterator Interface: Reset the iterator index
     */
    public function rewind(): void
    {
        $this->iterator = 0;
    }

    /**
     * Iterator Interface: Get current key
     */
    public function key(): mixed
    {
        return $this->iterator;
    }

    /**
     * Iterator Interface: Determine if the current iterator index is valid
     */
    public function valid(): bool
    {
        return isset($this->rules[$this->iterator]);
    }

    /**
     * ===============================================================
     * ======== Arrayable functions
     * ===============================================================
     */

    /**
     * Compile this object to an array
     */
    public function toArray(): array
    {
        return $this->rules;
    }

    /**
     * ===============================================================
     * ======== Core Validation Rules (for remainder of file)
     * ===============================================================
     */

    /**
     * @link https://laravel.com/docs/master/validation#rule-accepted
     */
    public function accepted(): self
    {
        return $this->rule('accepted');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-accepted-if
     */
    public function acceptedIf(string ...$fieldsAndValues): self
    {
        return $this->rule('accepted_if'.self::arguments($fieldsAndValues));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-active-url
     */
    public function activeUrl(): self
    {
        return $this->rule('active_url');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-after
     */
    public function after(string|DateTimeInterface $date): self
    {
        return $this->rule('after'.self::arguments($date));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-after-or-equal
     */
    public function afterOrEqual(string|DateTimeInterface $date): self
    {
        return $this->rule('after_or_equal'.self::arguments($date));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-alpha
     */
    public function alpha(string $range = null): self
    {
        return $this->rule('alpha'.self::arguments($range));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-alpha-dash
     */
    public function alphaDash(string $range = null): self
    {
        return $this->rule('alpha_dash'.self::arguments($range));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-alpha-numeric
     */
    public function alphaNumeric(string $range = null): self
    {
        return $this->rule('alpha_numeric'.self::arguments($range));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-array
     */
    public function array(string ...$keys): self
    {
        return $this->rule('array'.self::arguments($keys));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-ascii
     */
    public function ascii(): self
    {
        return $this->rule('ascii');
    }

    /**
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
     * @link https://laravel.com/docs/master/validation#rule-before
     */
    public function before(string|DateTimeInterface $date): self
    {
        return $this->rule('before'.self::arguments($date));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-before-or-equal
     */
    public function beforeOrEqual(string|DateTimeInterface $date): self
    {
        return $this->rule('before_or_equal'.self::arguments($date));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-between
     */
    public function between(int $min, int $max): self
    {
        return $this->rule('between:'.$min.','.$max);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-boolean
     */
    public function boolean(): self
    {
        return $this->rule('boolean');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-confirmed
     */
    public function confirmed(): self
    {
        return $this->rule('confirmed');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-current-password
     */
    public function currentPassword(string $guard = null): self
    {
        return $this->rule('current_password'.self::arguments($guard));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-date
     */
    public function date(): self
    {
        return $this->rule('date');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-date-equals
     */
    public function dateEquals(string|DateTimeInterface $date): self
    {
        return $this->rule('date_equals'.self::arguments($date));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-date-format
     */
    public function dateFormat(string ...$format): self
    {
        return $this->rule('date_format'.self::arguments($format));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-decimal
     */
    public function decimal(int $min, int $max = null): self
    {
        $max ??= $min;

        return $this->rule('decimal:'.$min.','.$max);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-declined
     */
    public function declined(): self
    {
        return $this->rule('declined');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-declined-if
     */
    public function declinedIf(string ...$fieldsAndValues): self
    {
        return $this->rule('declined_if'.self::arguments($fieldsAndValues));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-different
     */
    public function different(string $field): self
    {
        return $this->rule('different:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-digits
     */
    public function digits(int $value): self
    {
        return $this->rule('digits:'.$value);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-digits-between
     */
    public function digitsBetween(int $min, int $max): self
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
     * @link https://laravel.com/docs/master/validation#rule-distinct
     */
    public function distinct(string $mode = null): self
    {
        return $this->rule('distinct'.self::arguments($mode));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-doesnt-start-with
     */
    public function doesntStartWith(string ...$prefixes): self
    {
        return $this->rule('doesnt_start_with'.self::arguments($prefixes));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-doesnt-end-with
     */
    public function doesntEndWith(string ...$prefixes): self
    {
        return $this->rule('doesnt_end_with'.self::arguments($prefixes));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-email
     */
    public function email(string ...$flags): self
    {
        return $this->rule('email'.self::arguments($flags));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-ends-with
     */
    public function endsWith(string ...$prefixes): self
    {
        return $this->rule('ends_with'.self::arguments($prefixes));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-enum
     */
    public function enum(string $enum): self
    {
        return $this->rule(RuleClass::enum($enum));
    }

    /**
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
     * @param  callable|bool  $condition
     * @link https://laravel.com/docs/master/validation#rule-exclude-if
     */
    public function excludeIf($condition): self
    {
        return $this->rule(RuleClass::excludeIf($condition));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-exclude-unless
     */
    public function excludeUnless(string ...$fieldsAndValues): self
    {
        return $this->rule('exclude_unless'.self::arguments($fieldsAndValues));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-exclude-with
     */
    public function excludeWith(string $field): self
    {
        return $this->rule('exclude_with:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-exclude-without
     */
    public function excludeWithout(string $field): self
    {
        return $this->rule('exclude_without:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-exists
     */
    public function exists(string $table, string $column = null): self
    {
        return $this->rule(RuleClass::exists($table, $column ?? 'NULL'));
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
     * @link https://laravel.com/docs/master/validation#rule-greater-than
     */
    public function greaterThan(string $field): self
    {
        return $this->rule('gt:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-greater-than-or-equal
     */
    public function greaterThanOrEqual(string $field): self
    {
        return $this->rule('gte:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-image
     */
    public function image(): self
    {
        return $this->rule('image');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-in
     */
    public function in(string ...$values): self
    {
        return $this->rule('in'.self::arguments($values));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-in-array
     */
    public function inArray(string $field): self
    {
        return $this->rule('in_array:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-integer
     */
    public function integer(): self
    {
        return $this->rule('integer');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-ip
     */
    public function ip(): self
    {
        return $this->rule('ip');
    }

    /**
     * @link https://laravel.com/docs/master/validation#ipv4
     */
    public function ipv4(): self
    {
        return $this->rule('ipv4');
    }

    /**
     * @link https://laravel.com/docs/master/validation#ipv6
     */
    public function ipv6(): self
    {
        return $this->rule('ipv6');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-json
     */
    public function json(): self
    {
        return $this->rule('json');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-less-than
     */
    public function lt(string $field): self
    {
        return $this->rule('lt:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-less-than-or-equal
     */
    public function lte(string $field): self
    {
        return $this->rule('lte:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-lowercase
     */
    public function lowercase(): self
    {
        return $this->rule('lowercase');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-mac-address
     */
    public function macAddress(): self
    {
        return $this->rule('mac_address');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-max
     */
    public function max(int $value): self
    {
        return $this->rule('max:'.$value);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-max-digits
     */
    public function maxDigits(int $digits): self
    {
        return $this->rule('max_digits:'.$digits);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-mime-types
     */
    public function mimeTypes(string ...$types): self
    {
        return $this->rule('mimetypes'.self::arguments($types));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-mime-type-by-file-extension
     */
    public function mimes(string ...$extensions): self
    {
        return $this->rule('mimes'.self::arguments($extensions));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-min
     */
    public function min(int $value): self
    {
        return $this->rule('min:'.$value);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-min-digits
     */
    public function minDigits(int $value): self
    {
        return $this->rule('min_digits:'.$value);
    }

    /**
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
     * @link https://laravel.com/docs/master/validation#rule-missing-if
     */
    public function missingIf(string ...$fieldsAndValues): self
    {
        return $this->rule('missing_if'.self::arguments($fieldsAndValues));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-missing-unless
     */
    public function missingUnless(string ...$fieldsAndValues): self
    {
        return $this->rule('missing_unless'.self::arguments($fieldsAndValues));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-missing-with
     */
    public function missingWith(string ...$fields): self
    {
        return $this->rule('missing_with'.self::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-missing-with-all
     */
    public function missingWithAll(string ...$fields): self
    {
        return $this->rule('missing_with_all'.self::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-multiple-of
     */
    public function multipleOf(int $value): self
    {
        return $this->rule('multiple_of:'.$value);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-not-in
     */
    public function notIn(string ...$values): self
    {
        return $this->rule('not_in'.self::arguments($values));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-not-regex
     */
    public function notRegex(string $regex): self
    {
        return $this->rule('not_regex:'.$regex);
    }

    /**
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
     * @link https://laravel.com/docs/master/validation#rule-numeric
     */
    public function numeric(): self
    {
        return $this->rule('numeric');
    }

    /**
     * @param string|array<mixed> $rules
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
     * @link https://laravel.com/docs/master/validation#rule-present
     */
    public function present(): self
    {
        return $this->rule('present');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-prohibited
     */
    public function prohibited(): self
    {
        return $this->rule('prohibited');
    }

    /**
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
     * @link https://laravel.com/docs/master/validation#rule-prohibits
     */
    public function prohibits(string ...$fields): self
    {
        return $this->rule('prohibits'.self::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-regular-expression
     */
    public function regex(string $pattern): self
    {
        return $this->rule('regex:'.$pattern);
    }

    /**
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
     * @link https://laravel.com/docs/master/validation#rule-required-if
     */
    public function requiredIf(
        string|bool|RequiredIf $condition = null,
        string ...$fieldsAndValues
    ): self {
        if (is_bool($condition)) {
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
     * @link https://laravel.com/docs/master/validation#rule-required-unless
     */
    public function requiredUnless(
        string|bool $condition = null,
        string ...$fieldsAndValues
    ): self {
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
     */
    public function requiredWith(string ...$fields): self
    {
        return $this->rule('required_with'.self::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required-with-all
     */
    public function requiredWithAll(string ...$fields): self
    {
        return $this->rule('required_with_all'.self::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required-without
     */
    public function requiredWithout(string ...$fields): self
    {
        return $this->rule('required_without'.self::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required-without-all
     */
    public function requiredWithoutAll(string ...$fields): self
    {
        return $this->rule('required_without_all'.self::arguments($fields));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-required-array-keys
     */
    public function requiredArrayKeys(string ...$keys): self
    {
        return $this->rule('required_array_keys'.self::arguments($keys));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-same
     */
    public function same(string $field): self
    {
        return $this->rule('same:'.$field);
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-size
     */
    public function size(string|int $value): self
    {
        return $this->rule('size:'.$value);
    }

    /**
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
     * @link https://laravel.com/docs/master/validation#rule-starts-with
     */
    public function startsWith(string ...$values): self
    {
        return $this->rule('starts_with'.self::arguments($values));
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-string
     */
    public function string(): self
    {
        return $this->rule('string');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-timezone
     *
     * @todo support new timezone rule config
     */
    public function timezone(): self
    {
        return $this->rule('timezone');
    }

    /**
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
     * @link https://laravel.com/docs/master/validation#rule-uppercase
     */
    public function uppercase(): self
    {
        return $this->rule('uppercase');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-url
     */
    public function url(): self
    {
        return $this->rule('url');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-ulid
     */
    public function ulid(): self
    {
        return $this->rule('ulid');
    }

    /**
     * @link https://laravel.com/docs/master/validation#rule-uuid
     */
    public function uuid(): self
    {
        return $this->rule('uuid');
    }
}
