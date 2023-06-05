<?php

declare(strict_types=1);

namespace BradieTilley\Rules\Concerns;

use BradieTilley\Rules\Rule;
use Iterator;

/**
 * This traits provides the necessary functionality required for the
 * Iterator interface, which we use to allow this Rule object to be
 * iterated inside a Validator as if it's an array.
 *
 * @mixin Rule
 * @mixin Iterator
 */
trait IteratesRules
{
    private int $iterator = 0;

    /**
     * Iterator Interface: Get the iterator's current value
     */
    public function current(): mixed
    {
        return $this->rules[$this->iterator];
    }

    /**
     * Iterator Interface: Set the next iterator index
     */
    public function next(): void
    {
        $this->iterator++;
    }

    /**
     * Iterator Interface: Set the previous iterator index
     */
    public function prev(): void
    {
        $this->iterator--;
    }

    /**
     * Iterator Interface: Reset the iterator index
     */
    public function rewind(): void
    {
        $this->iterator = 0;
    }

    /**
     * Iterator Interface: Get current key
     */
    public function key(): mixed
    {
        return $this->iterator;
    }

    /**
     * Iterator Interface: Determine if the current iterator index is valid
     */
    public function valid(): bool
    {
        return isset($this->rules[$this->iterator]);
    }
}
