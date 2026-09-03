<?php 
function package_asset(string $path)
{
    $asset = asset("vendor/livewire-jalali-date-picker/$path");
    $path = file_exists($asset) ? $asset
                                : url("assets/$path");
    return $path;
}