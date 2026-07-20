# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed

- Target **Laravel 12** exclusively; Laravel 11 and earlier are no longer supported on this major line
- Raise the minimum PHP version to **8.2**
- Replace the `illuminate/container` dependency with `illuminate/validation` (`^12.0`)
- Bump development tooling: Orchestra Testbench `^10`, Pest `^3`, PHPStan `^2`
- CI now tests PHP **8.2**, **8.3**, and **8.4**
- Autoload the `rule()` helper via Composer's `files` autoload so it is available without a manual require
- `in()`, `notIn()`, and `contains()` now build rules through Laravel's fluent `Illuminate\Validation\Rule` helpers (values are quoted, e.g. `in:"foo","bar"`)
- Explicit nullable parameter types for PHP 8.4 compatibility (e.g. `?string $range = null` instead of implicitly nullable `string $range = null`)

### Added

- `anyOf(array $rules)`
- `doesntContain(string ...$values)`
- `encoding(string $encoding)`
- `inArrayKeys(string ...$keys)`
- `prohibitedIfAccepted(string ...$fields)`
- `prohibitedIfDeclined(string ...$fields)`
- Optional custom confirmation field: `confirmed(?string $field = null)`
- Strict mode for numeric rules: `integer(bool $strict = false)`, `numeric(bool $strict = false)`
- URL protocol restriction: `url(string ...$protocols)`
- UUID version restriction: `uuid(string|int|null $version = null)`

### Fixed

- `alphaNumeric()` now emits the correct Laravel rule string `alpha_num` (was incorrectly `alpha_numeric`)
- `Rule::make()` no longer passes an unused `$field` argument into the Rule constructor (which could error under PHP 8+)
- README password example updated to use `Password::min(...)->letters()->numbers()` instead of invalid named arguments

### Breaking changes

| Change | Migration |
| --- | --- |
| Laravel 11 (and earlier) dropped | Upgrade the application to Laravel 12, then install this major version |
| PHP &lt; 8.2 dropped | Run on PHP 8.2 or higher |
| `illuminate/container` no longer required directly | No action for normal Laravel apps; `illuminate/validation` is required instead |
| `alphaNumeric()` rule string corrected to `alpha_num` | Update any assertions or custom code that expected `alpha_numeric` |
| `in()` / `notIn()` / `contains()` string form now quotes values | Update assertions that expected unquoted forms like `in:foo,bar` to `in:"foo","bar"` |
| `Rule::make(?string $field)` no longer forwards `$field` to the constructor | If you subclassed `Rule` and relied on a constructor `$field` argument, stop depending on it (the argument remains accepted for call-site compatibility but is ignored) |

Optional method parameters such as `confirmed()`, `integer()`, `numeric()`, `url()`, and `uuid()` remain backwards compatible when called with no arguments.
