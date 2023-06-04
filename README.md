# Laravel Rules

![example usage](/docs/example-usage.png)

![Static Analysis](https://github.com/bradietilley/rules/actions/workflows/static.yml/badge.svg)
![Tests](https://github.com/bradietilley/rules/actions/workflows/tests.yml/badge.svg)


## Introduction

Rules provides an elegant chainable object-oriented approach to defining rules for Laravel Validation.


## Installation

```
composer require bradietilley/rules
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

The `rule()` method acts as a catch-all to support any rule you need to chuck in there.

For example:

```php
Rule::make()
    /** You can use the methods provided*/
    ->required()
    /** Or you can pass in any raw string rule */
    ->rule('min:2')
    /** Or you can pass in any array of rules */
    ->rule([
        'max:2',
        new Unique('table', 'column'),
    ])
    /** Or you can pass in a `Rule` object             - this interface is deprecated and will be dropped in 11.x */
    ->rule(new RuleThatImplementsRule())
    /** Or you can pass in a `InvokableRule` object    - this interface is deprecated and will be dropped in 11.x */
    ->rule(new RuleThatImplementsInvokableRule())
    /** Or you can pass in a `ValidationRule` object   - this interface the only rule interface you should use */
    ->rule(new RuleThatImplementsValidationRule());
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


### Benefits

**Better syntax**

Similar to chaining DB column schema definitions in migrations, this package aims to provide a clean, elegant chaining syntax.

**Parameter Insights**

When dealing with string-based validation rules such as `decimal`, remembering what the available parameters can become a nuisance. As methods, you can get autocompletion and greater insights into the parameters available, along with a quick `@link` to the validation rule.

**Variable Injection**

Instead of concatenating variables in an awkward manner like `'min:'.getMinValue()` you can clearnly inject variables as method arguments like `->min(getMinValue())`

**Conditional Logic**

If you're like me, using a single request class for Creating and Updating a resource makes sense 99% of the time, but for the 99% of the time you're faced with the issue of making a field `required` for create, but then making it either `nullable` or `sometimes` for updates. [ConditionalRules](#conditional-rules) resolves this.

### Performance

The performance of this packages varies as does natural PHP execution time. A single validator test that tests a string and integer with varying validity (based on min/max rules) results in a range of -20 microseconds to 20 microseconds difference, with an average of a 14 microsecond delay.

The more the package is utilised in a single request, the less relative overhead is seen. For example, running the same validation rules with 20 varying strings and integers can result in an average of 9 microseconds or even less.

The overhead here is no more a concern than using Laravel itself. If you're itching for those few microseconds worth of savings then you're probably better off running a custom lightweight framework not Laravel.

## Author

- [Bradie Tilley](https://github.com/bradietilley)
