# Laravel Rules

Rules provides an elegant chainable object-oriented approach to defining rules for Laravel Validation.

![Static Analysis](https://github.com/bradietilley/laravel-rules/actions/workflows/static.yml/badge.svg)
![Tests](https://github.com/bradietilley/laravel-rules/actions/workflows/tests.yml/badge.svg)

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

Quick example:

```php
use BradieTilley\Rules\Rule;

return [
    'my_field' => Rule::make()->required()->string(),
];
```

This produces a ruleset of the following (when passed to a `Validator` instance or returned from a your `Request::rules()` method):

```php
[
    'my_field' => [
        'required',
        'string',
    ],
]
```

### Available Rules

Every rule you're familiar with in Laravel will work with this package. Each core rule, such as `required`, `string`, `min`, etc, are available using their respective methods of the same name. Parameters for each rule (such as min `3` and max `4`) in `digitsBetween:3,4` are made available as method arguments, such as: `->digitsBetween(min: 3, max: 4)`.

The `->rule()` method acts as a catch-all to support any rule you need to chuck in there.

For example:

```php
Rule::make()
    /**
     * You can use the methods provided
     */
    ->required()

    /**
     * You can pass in any raw string rule as per "default Laravel"
     */
    ->rule('min:2')

    /**
     * You can pass in another Rule object which will be merged in
     */
    ->rule(Rule::make()->max(255))

    /**
     * You can pass in a `\Illuminate\Contracts\Validation\Rule` object
     *
     * Note: This Laravel interface is deprecated and will be dropped in 11.x
     */
    ->rule(new RuleThatImplementsRule())

    /**
     * You can pass in a `\Illuminate\Contracts\Validation\InvokableRule` object
     *
     * Note: This Laravel interface is deprecated and will be dropped in 11.x
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
        'max:2',
        new Unique('table', 'column'),
    ]);
```

### Conditional Rules

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

// or 

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

The conditional rules that you provide (in the example above: required and sometimes) may be of any variable type that is supported by the `->rule()` method ([as documented here](#available-rules)).

### Reusable Rules

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
function integerRule()
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

### Macros

This package allows you to define "macros" which can serve as a fluent to configure common rules.

For example, the following code adds a `australianPhoneNumber` method to the `Rule` class:

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

### Benefits

**Better syntax**

Similar to chaining DB column schema definitions in migrations, this package aims to provide a clean, elegant chaining syntax.

**Parameter Insights**

When dealing with string-based validation rules such as `decimal`, remembering what the available parameters can become a nuisance. As methods, you can get autocompletion and greater insights into the parameters available, along with a quick `@link` to the validation rule.

**Variable Injection**

Instead of concatenating variables in an awkward manner like `'min:'.getMinValue()` you can clearnly inject variables as method arguments like `->min(getMinValue())`

**Conditional Logic**

Easily add validation rules based on conditions, using the `->when()` and `->unless()` methods. 

**Wide Support of Rules**

Not only does it support all core-Laravel rules, but it also supports any custom rule classes that you define.

**Full Customisable**

Full customisation using [macros](#macros), [conditional rules](#conditional-rules) and [reusable rules](#reusable-rules).


### Performance

The performance of this packages varies as does natural PHP execution time. A single validator test that tests a string and integer with varying validity (based on min/max rules) results in a range of -20 microseconds to 20 microseconds difference, with an average of a 14 microsecond delay.

The more the package is utilised in a single request, the less relative overhead is seen. For example, running the same validation rules with 20 varying strings and integers can result in an average of 9 microseconds or even less.

The overhead here is no more a concern than using Laravel itself. If you're itching for those few microseconds worth of savings then you're probably better off running a custom lightweight framework -- not Laravel.


## Author

- [Bradie Tilley](https://github.com/bradietilley)
