<?php

use BradieTilley\Rules\Rule;

beforeAll(fn () => Rule::withHistory());
afterAll(fn () => Rule::withoutHistory());

it('applies the `accepted` rule', function () {
    Rule::make('my_field')->accepted();

    Rule::assertApplied('my_field', 'accepted');
});
