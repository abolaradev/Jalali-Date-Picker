<?php

use Illuminate\Support\Carbon;

return [
    // The timezone used for date calculations.
    'timezone' => 'Asia/Tehran',

    // The earliest date that can be selected.
    'minDate' => Carbon::now()
                       ->lastOfYear()
                       ->subCentury(),
    
                       
    // The latest date that can be selected                  
    'maxDate' => Carbon::now()
                       ->lastOfYear()
                       ->addYear(),

    // Automatically closes the calendar after selecting a date.
    'autoClose' => true,

    // Closes the calendar when clicking outside of it.
    'closeOnOutsideClick' => true,

    // Displays abbreviated names for the weekdays.
    'abbreviatingWeekdays' => true,

    // Converts English digits to Persian digits in the calendar.
    'withPersianDigits' => false,

    // Displays a button for clearing the selected date.
    'showResetDateButton' => true,

    // The primary Tailwind CSS color used by the DatePicker.
    // Supported values can be Tailwind color names such as:
    // stone, gray, zinc, neutral, slate , red, amber,lime, green, emerald, teal, cyan, sky, blue , ...
    'color' => 'blue',
];