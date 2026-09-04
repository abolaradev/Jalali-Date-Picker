<?php

use Abolaradev\JalaliDatePicker\Facades\JalaliDatePicker;

function package_asset(string $path)
{
    $asset = asset("vendor/livewire-jalali-date-picker/$path");
    $path = file_exists($asset) ? $asset
                                : url("assets/$path");
    return $path;
}

function getDay(string $date)
{
    return JalaliDatePicker::getDayFromDateString($date);
}

function isToday(string $date)
{
    return JalaliDatePicker::isTodayFromDateString($date);
}