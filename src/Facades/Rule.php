<?php

namespace BradieTilley\Rules\Facades;

use Illuminate\Support\Facades\Facade;
use BradieTilley\Rules\Rule as Base;

/**
 * @method static Base bail(string $field = null)
 * @method static Base exclude(string $field = null)
 * @method static Base filled(string $field = null)
 * @method static Base make(string $field = null)
 * @method static Base missing(string $field = null)
 * @method static Base nullable(string $field = null)
 * @method static Base required(string $field = null)
 * @method static Base sometimes(string $field = null)
 * @method static void assertApplied(string $field, string $rule = null, Closure $callback = null)
 *
 * @see Base
 */
class Rule extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Base::class;
    }
}
