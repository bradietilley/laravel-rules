<?php

namespace BradieTilley\Rules\Laravel;

use BradieTilley\Rules\Rule;
use Illuminate\Support\ServiceProvider;

class RuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('ruleset', Rule::class);
    }
}
