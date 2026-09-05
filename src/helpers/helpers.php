<?php

use Abolaradev\JalaliDatePicker\Facades\JalaliDatePicker;

function package_asset(string $path)
{
    $asset = asset("vendor/livewire-jalali-date-picker/$path");
    $path = file_exists($asset) ? $asset
                                : url()->query("assets/$path" ,['ver'=>time()]);
    return $path;
}


function isToday(string $date)
{
    return JalaliDatePicker::isTodayFromDateString($date);
}