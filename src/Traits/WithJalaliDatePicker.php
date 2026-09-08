<?php

namespace Abolaradev\JalaliDatePicker\Traits;

use DateTimeZone;

trait WithJalaliDatePicker {

   /**
    * The earliest date that can be selected.
    */
   public mixed $minDate;

   /**
    * The latest date that can be selected.
    */
   public mixed $maxDate;

   /**
   * Determines whether the calendar should close after selecting a date.
   */
   public bool $autoClose;

   /**
    * Specifies that the calendar closes when clicking outside its area.
    */
   public bool $closeOnOutsideClick; 

   /**
    * Specifies whether the days of the week are displayed in full or abbreviated form.
    */
   public bool $abbreviatingWeekdays;

   /**
    * Display calendar day numbers in Persian.
    */
   public bool $withPersianDigits;

   /**
    * Display the button to reset the selected date.
    */
   public bool $showResetDateButton;

   /**
    * Set calendar color
    */
   public string $color;


   public function mount(
     string $minDate = null,
     string $maxDate = null,
     bool $autoClose = null,
     bool $closeOnOutsideClick = null,
     bool $abbreviatingWeekdays = null ,
     bool $withPersianDigits = null ,
     bool $showResetDateButton = null ,
     string $color = null
   )
   {
     $config=config('jalali-date-picker');

     $this->minDate = $minDate ?? $config['minDate'];
     $this->maxDate = $maxDate ?? $config['maxDate'];
     $this->autoClose = $autoClose ?? $config['autoClose'];
     $this->closeOnOutsideClick = $closeOnOutsideClick ?? $config['closeOnOutsideClick'];
     $this->abbreviatingWeekdays = $abbreviatingWeekdays ?? $config['abbreviatingWeekdays'];
     $this->withPersianDigits = $withPersianDigits ?? $config['withPersianDigits'];
     $this->showResetDateButton = $showResetDateButton ?? $config['showResetDateButton'];
     $this->color = $color ?? $config['color'];     
   }
}