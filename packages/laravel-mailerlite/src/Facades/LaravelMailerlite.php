<?php

namespace Ihasan\LaravelMailerlite\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ihasan\LaravelMailerlite\LaravelMailerlite
 */
class LaravelMailerlite extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Ihasan\LaravelMailerlite\LaravelMailerlite::class;
    }
}
