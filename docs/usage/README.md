# Usage

## Quick overview

```php
use BradieTilley\Rules\Rule;

return [
    'my_field' => Rule::make()->required()->string(),
];
```

This produces a ruleset of the following (when passed to a `Validator` instance or returned from a `\Illuminate\Foundation\Http\FormRequest->rules()` method):

```php
[
    'my_field' => [
        'required',
        'string',
    ],
]
```

## Available rules

Every rule you're familiar with in Laravel will work with this package. Each core rule, such as `required`, `string`, `min`, etc, are also available using their respective methods of the same name in camelCase. Parameters for each rule (such as min `3` and max `4`) in `digitsBetween:3,4` are made available as method arguments, such as: `->digitsBetween(min: 3, max: 4)`.

Laravel 12 additions such as `anyOf`, `doesntContain`, `encoding`, `inArrayKeys`, `prohibitedIfAccepted`, and `prohibitedIfDeclined` are supported, along with newer rule options like `->integer(strict: true)`, `->numeric(strict: true)`, `->url('http', 'https')`, `->uuid(4)`, and `->confirmed('repeat_username')`.

`in()` and `notIn()` accept `string` or `UnitEnum` values and build rules through Laravel's fluent `Illuminate\Validation\Rule` helpers.

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

## Conditional rules

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

The conditional rules that you provide (in the example above: `required` and `sometimes`) may be of any variable type that is supported by the [`->rule()` method](#available-rules).

## Reusable rules

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
    };

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

The `->with(...)` method accepts any form of `callable`, such as:

- Closures (e.g. `function () {  }`)
- Traditional callable notations (e.g. `[$this, 'methodName']`)
- First-class callables (e.g. `$this->methodName(...)`)
- Invokable classes (e.g. a class with the `__invoke` magic method)
- Whatever else PHP defines as `callable`
