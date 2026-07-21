# Customisation

## Macros

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

## Custom `Rule` class

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
