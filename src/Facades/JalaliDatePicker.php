<?php

namespace Abolaradev\JalaliDatePicker\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Abolaradev\JalaliDatePicker\JalaliDatePicker
 */
class JalaliDatePicker extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Abolaradev\JalaliDatePicker\JalaliDatePicker::class;
    }
}
