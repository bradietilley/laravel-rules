# Laravel Rules

Fluent, chainable validation rules for Laravel.

```php
use BradieTilley\Rules\Rule;
use Illuminate\Validation\Rules\Password;

return [
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
            Password::min(8)->letters()->numbers(),
        ),
];
```

## Documentation

- [Installation](installation/README.md)
- [Usage](usage/README.md)
- [Customisation](customisation/README.md)
- [ValidationRule helper](validation-rule/README.md)

## Requirements

- PHP 8.2+
- Laravel 12+

## Quick start

```bash
composer require bradietilley/laravel-rules
```

```php
use BradieTilley\Rules\Rule;

return [
    'my_field' => Rule::make()->required()->string(),
];
```

See [Installation](installation/README.md) and [Usage](usage/README.md) for full walkthroughs.

## Benefits

- **Better syntax** — chain rules like migration column definitions
- **Parameter insights** — method arguments with autocompletion and `@link` to Laravel docs
- **Variable injection** — `->min(getMinValue())` instead of `'min:'.getMinValue()`
- **Conditional logic** — `->when()`, `->unless()`, and rule methods that accept conditions
- **Wide support** — all core Laravel rules plus custom rule classes
- **Fully customisable** — macros, reusable callables, and custom `Rule` subclasses

## Performance

Overhead is typically in the low tens of microseconds per validator run and is considered negligible. Relative cost drops further as more fields use the package in a single request.
