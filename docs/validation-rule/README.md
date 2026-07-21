# ValidationRule helper

An **optional**, different approach to a typical implementation of the `ValidationRule` interface is the `BradieTilley\Rules\Validation\ValidationRule` class which handles the signature of the `Closure $fail` argument and forced `void` return type inside the abstract class, allowing your rule classes to ship with cleaner syntax.

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

## Why

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
