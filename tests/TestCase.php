<?php

declare(strict_types=1);

namespace Tests;

use Orchestra\Testbench\TestCase as TestbenchTestCase;

abstract class TestCase extends TestbenchTestCase
{
    public static array $history = [];

    protected function setUp(): void
    {
        parent::setUp();

        self::$history = [];
    }
}
