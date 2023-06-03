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

You can also use the `Rule` facade, however if performance is ideal then it'e recommended not to use it.

```php
use BradieTilley\Rules\Rule;

Rule::make()->required()->string();
```

vs

```
use BradieTilley\Rules\Facades\Rule;

Rule::required()->string();
```

Both produce a ruleset of:

```php
[
    "required",
    "string",
]
```

...

## Author

- [Bradie Tilley](https://github.com/bradietilley)
