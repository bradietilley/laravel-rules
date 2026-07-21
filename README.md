# Laravel Rules

Fluent, chainable validation rules for Laravel.

![Static Analysis](https://github.com/bradietilley/laravel-rules/actions/workflows/static.yml/badge.svg)
![Tests](https://github.com/bradietilley/laravel-rules/actions/workflows/tests.yml/badge.svg)
![Laravel Version](https://img.shields.io/badge/Laravel%20Version-12.x-F9322C)
![PHP Version](https://img.shields.io/badge/PHP%20Version-8.2%2B-4F5B93)

## Documentation

Full documentation is available at [bradietilley.dev/laravel-rules](https://bradietilley.dev/laravel-rules).

## Installation

```bash
composer require bradietilley/laravel-rules
```

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

See the [documentation](https://bradietilley.dev/laravel-rules) for usage, customisation, and the optional `ValidationRule` helper.

## Credits

- [Bradie Tilley](https://github.com/bradietilley)
