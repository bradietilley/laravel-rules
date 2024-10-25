# Laravel Rules

Rules provides an elegant chainable object-oriented approach to defining rules for Laravel Validation.

![Static Analysis](https://github.com/bradietilley/laravel-rules/actions/workflows/static.yml/badge.svg)
![Tests](https://github.com/bradietilley/laravel-rules/actions/workflows/tests.yml/badge.svg)
![Laravel Version](https://img.shields.io/badge/Laravel%20Version-11.x-F9322C)
![PHP Version](https://img.shields.io/badge/PHP%20Version-8.3-4F5B93)


```php
    'email' => Rule::make()
        ->bail()
        ->when(
            $this->method() === 'POST',
            Rule::make()->required(),
            Rule::make()->sometimes(),
        )
        ->string()
        ->email()
        ->unique(
            table: User::class,
            column: 'email',
            ignore: $this->route('user')?->id,
        ),
    'password' => rule()
        ->bail()
        ->when(
            $this->method() === 'POST',
            rule()->required(),
            rule()->sometimes(),
        )
        ->string()
        ->password(
            min: 8,
            letters: true,
            numbers: true,
        ),
```


## Installation

Grab it via composer

```
composer require bradietilley/laravel-rules
```


## Documentation

### Rules → A quick overview

```php
use BradieTilley\Rules\Rule;

return [
    'my_field' => Rule::make()->required()->string(),
];
```

This produces a ruleset of the following (when passed to a `Validator` instance or returned from a your `\Illuminate\Foundation\Http\FormRequest->rules()` method):

```php
[
    'my_field' => [
        'required',
        'string',
    ],
]
```

### Rules → Available rules

Every rule you're familiar with in Laravel will work with this package. Each core rule, such as `required`, `string`, `min`, etc, are also available using their respective methods of the same name in camelCase. Parameters for each rule (such as min `3` and max `4`) in `digitsBetween:3,4` are made available as method arguments, such as: `->digitsBetween(min: 3, max: 4)`.

The `->rule()` method acts as a catch-all to support any rule you need to chuck in there, such as in the short interim after new rules are added and support is added to this package.

For example:

```php
Rule::make()
    /**
     * You can use the methods provided
     */
    ->required()

    /**
     * You can pass in any raw string rule as per default in Laravel
     */
    ->rule('min:2')

    /**
     * You can pass in another Rule object which will be merged in
     */
    ->rule(Rule::make()->max(255))

    /**
     * You can pass in a `\Illuminate\Contracts\Validation\Rule` object
     *
     * Note: This Laravel interface is deprecated and will be dropped in future versions of Laravel. It is recommended to not use this interface.
     */
    ->rule(new RuleThatImplementsRule())

    /**
     * You can pass in a `\Illuminate\Contracts\Validation\InvokableRule` object
     *
     * Note: This Laravel interface is deprecated and will be dropped in future versions of Laravel. It is recommended to not use this interface.
     */
    ->rule(new RuleThatImplementsInvokableRule())

    /**
     * You can pass in a `\Illuminate\Contracts\Validation\ValidationRule` object
     */
    ->rule(new RuleThatImplementsValidationRule())

    /**
     * You can pass in any array of rules. The array values can be any of the
     * above rule types: strings, Rule objects, ValidationRule instances, etc
     */
    ->rule([
        Rule::make()->rule([
            'min:1',
        ]),
        'max:25',
        new Unique('table', 'column'),
    ]);
```

### Rules → Conditional rules

You may specify rules that are conditionally defined. For example, you may wish to make a field `required` on create, and `sometimes` on update. In this case you may define something like:

```php
public function rules(): array
{
    $create = $this->method() === 'POST';

    return [
        'name' => Rule::make()
            ->when($create, Rule::make()->required(), Rule::make()->sometimes())
            ->string(),
    ];
}

// or just chuck in the rules as string literals if you feel that's cleaner

public function rules(): array
{
    $create = $this->method() === 'POST';

    return [
        'name' => Rule::make()
            ->when($create, 'required', 'sometimes')
            ->string(),
    ];
}
```

The conditional rules that you provide (in the example above: `required` and `sometimes`) may be of any variable type that is supported by the `->rule()` method ([as documented here](#rules--available-rules)).

### Rules → Reusable rules

The `->with(...)` method in a rule offers you the flexibility you need to specify rule logic that can be re-used wherever you need it.

Here is an example:

```php
/**
 * Example using a closure
 */
public function rules(): array
{
    $integerRule = function (Rule $rule) {
        $rule->integer()->max(100);
    }

    return [
        'percent' => Rule::make()
            ->with($integerRule),
    ];
}

/**
 * Example using a first class callable
 */
function integerRule(Rule $rule)
{
    $rule->integer()->max(100);
}

public function rules(): array
{
    return [
        'percent' => Rule::make()
            ->with(integerRule(...)),
    ];
}

/**
 * Example using a callable invokable class
 */
class IntegerRule
{
    public function __invoke(Rule $rule)
    {
        $rule->integer()->max(100);
    }
}

public function rules(): array
{
    return [
        'percent' => Rule::make()
            ->with(new IntegerRule()),
    ];
}

/**
 * The above examples would all return:
 */
[
    'percent' => [
        'integer',
        'max:100',
    ],
]
```

The `->with(...)` method accepts any form of `callable`, such as

- Closures (e.g. `function () {  }`)
- Traditional callable notations (e.g. `[$this, 'methodName']`)
- First-class callables (e.g. `$this->methodName(...)`)
- Invokable classes (e.g. a class with the `__invoke` magic method)
- Whatever else PHP defines as `callable`.

### Customisation → Macros

This package allows you to define "macros" which can serve as a fluent way to configure common rules.

For example, the following code adds an `australianPhoneNumber` method to the `Rule` class:

```php
Rule::macro('australianPhoneNumber', function () {
    /** @var Rule $this */
    return $this->rule('regex:/^\+614\d{8}$/');
});

return [
    'phone' => Rule::make()
        ->required()
        ->string()
        ->australianPhoneNumber(),
];

/**
 * The above would return:
 */
[
    'phone' => [
        'required',
        'string',
        'regex:/^\+614\d{8}$/',
    ],
]
```

The downside to using Macros is the lack of auto-completion and intellisense. Macros are not for everyone.

### Customisation → Custom `Rule` class

You may wish to use your own `Rule` class to provide your own customisation. This can be achieved by registering your Rule class via your `AppServiceProvider` or a similar place.

```php
\BradieTilley\Rules\Rule::using(\App\Rules\CustomRule::class);

// via ::make()
\BradieTilley\Rules\Rule::make(); // instanceof App\Rules\CustomRule

// via the helper function
rule(); // instanceof App\Rules\CustomRule
```

This allows you to customise any aspect you wish:

```php
    public function email(string ...$flags): static
    {
        return parent::email(...$flags)->min(5)->max(255);
    }
```

```php
    CustomRule::make()->required()->email();

    // result:
    [
        'required',
        'email',
        'min:5',
        'max:255',
    ],
```

### About → Benefits

**Better syntax**

Similar to chaining DB column schema definitions in migrations, this package aims to provide a clean, elegant chaining syntax.

**Parameter Insights**

When dealing with string-based validation rules such as `decimal`, remembering what the available parameters can become a nuisance. As methods, you can get autocompletion and greater insights into the parameters available, along with a quick `@link` to the validation rule documentation, to better understand how the validation rule works.

**Variable Injection**

Instead of concatenating variables in an awkward manner like `'min:'.getMinValue()` you can clearnly inject variables as method arguments like `->min(getMinValue())`

**Conditional Logic**

Easily add validation rules based on conditions, using the `->when()` and `->unless()` methods, as well as by passing in conditional states into methods such as `->requiredIf($this->method() === 'POST')`.

**Wide Support of Rules**

Not only does it support all core-Laravel rules, but it also supports any custom rule classes that you define.

**Fully Customisable**

Full customisation using [macros](#customisation--macros), [conditional rules](#rules--conditional-rules), [reusable rules](#rules--reusable-rules) and [custom rule classes](#customisation--custom-rule-class)


### About → Performance

The performance of this packages varies as does natural PHP execution time. A single validator test that tests a string and integer with varying validity (based on min/max rules) results in a range of -20 microseconds to 20 microseconds difference, with an average of a 14 microsecond delay.

The more the package is utilised in a single request, the less relative overhead is seen. For example, running the same validation rules with 20 varying strings and integers can result in an average of 9 microseconds or even less.

The overhead here is considered negligible.


### Side Feature → The `ValidationRule` class

An **optional**, different approach to a typical implementation of the `ValidationRule` interface is the `BradieTilley\Rules\Validation\ValidationRule` class which hanldes the horrible signature of the `Closure $fail` argument and *forced* `void` return type inside the abstract class, allowing your rule classes to ship with cleaner syntax.

To get started, simply extend the `BradieTilley\Rules\Validation\ValidationRule` class in your custom rule class. And instead of defining a `validate` method, define the `run` method.

So instead of this:

```php
public function validate(string $attribute, mixed $value, Closure $fail): void
{
    if ($this->someCondition) {
        return; // pass
    }

    if ($this->otherCondition) {
        $fail('Some error message');

        return; // you can't return anything so you can't join the $fail and return lines together
    }
}
```

You would have this:

```php
public function run(string $attribute, mixed $value): static
{
    if ($this->someCondition) {
        return $this->pass();
    }

    if ($this->otherCondition) {
        return $this->fail('Some error message');
    }

    return $this->pass();
}
```

#### Why

**Single line failures**

Because of the `void` return type of the `validate` method, you cannot `return $fail('Some error message');` in a single line, and if you adhere to any of the common code styles out there you also have to have an empty line before a `return;` statement (unless it's the first line in a body). This cleans things up a bit by allowing that clean single line return:

```diff
-$fail('Some error message')
-
-return;
+return $this->fail('Some error message');
```

**Readability**

By enforcing a return type (`static` in this case), this forces you to specify at least some form of a response rather than ambiguous empty `return;` statements that get used for pass and failure results. Although you could `return $this;`, it obviously encourages you to do the right thing and return a result. This improves readability by forcing you to explain the exit result:

```diff
-if ($this->someCondition) {
-    return;
-}
+if ($this->someCondition) {
+    return $this->pass();
+}
```

**Failure syntax**

Invoking a method is always visually cleaner than invoking a `Closure` variable. This provides a cleaner syntax:

```diff
-$fail('Some error message')
+$this->fail('Some error message');
```

**Method signature**

The `$fail` parameter of the `validate` method is messy. It's a `Closure` that requires importing at the top, and if you enforce generics in your project, the `$fail` parameter requires a type hint that explains any arguments and return types. Removing this type hint means the docblock is purely to say _"Run the validation rule."_ which is superfluous and can be removed too.

```diff
-use Closure;

// ...

-    /**
-     * Run the validation rule.
-     *
-     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
-     */
-    public function validate(string $attribute, mixed $value, Closure $fail): void
+    public function run(string $attribute, mixed $value): static
    {
        // ...
    }
```

## Issues

If you spot any issues please feel free to open an Issue and/or PR and I'll address the issues.

## Author

- [Bradie Tilley](https://github.com/bradietilley)
