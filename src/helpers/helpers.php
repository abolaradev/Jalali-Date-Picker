<?php

use Abolaradev\JalaliDatePicker\Facades\JalaliDatePicker;

function package_asset(string $path)
{
   $filePath = public_path("vendor/jalali-date-picker/$path");

    return file_exists($filePath)
           ? asset("vendor/jalali-date-picker/$path")
           : url()->query("assets/$path" ,['ver'=>time()]);
}
