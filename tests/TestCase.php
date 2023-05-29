<?php

namespace Tests;

use Orchestra\Testbench\TestCase as TestbenchTestCase;

abstract class TestCase extends TestbenchTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            \BradieTilley\Rules\Laravel\RuleServiceProvider::class,
        ];
    }
}
