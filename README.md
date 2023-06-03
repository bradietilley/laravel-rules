# Laravel Rules

![example usage](/docs/example-usage.png)

![Static Analysis](https://github.com/bradietilley/rules/actions/workflows/static.yml/badge.svg)
![Tests](https://github.com/bradietilley/rules/actions/workflows/tests.yml/badge.svg)


## Introduction

Rules provides an elegant chainable OO approach to defining rules for Laravel Validation.


## Installation

```
composer require bradietilley/rules
```


## Documentation

Rules can be fairly performance friendly with an approximated 5-10% overhead compared to standard array-based validation rules.

```php
use BradieTilley\Rules\Rule;

Rule::make()->required()->string();
```

Which produces a ruleset of the following when passed to a `Validator` instance.

```php
[
    "required",
    "string",
]
```

...

## Author

- [Bradie Tilley](https://github.com/bradietilley)
