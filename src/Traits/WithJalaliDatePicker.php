<?php

namespace Abolaradev\JalaliDatePicker\Traits;

use Abolaradev\JalaliDatePicker\Facades\JalaliDatePicker;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

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

     
  /**
  * It is being checked whether the selected date is today.
  *
  * @param  string $date The Jalali date string
  * @return bool
  */
  public function isToday(string $date) :bool
  {
    return JalaliDatePicker::isToday($date);
  }


  /**
   * Checks whether a specific date is earlier than the minimum specified date.
   *
   * @param  string $date The Jalali date string.
   * @return bool
   */
  public function isLessThanMinDate(string $date) :bool
  {
    return JalaliDatePicker::isDateWithinMinDate($date);
  }


  /**
   * Checks whether a specific date is later than the maximum specified date.
   *
   * @param  string $date The Jalali date string.
   * @return bool
   */
  public function isGreaterThanMaxDate(string $date) :bool
  {
    return JalaliDatePicker::isDateWithinMaxDate($date);
  }

  
  /**
   * Getting the days of the week.
   *
   * @return Collection
   */
  #[Computed()]  
  public function weekdays() :Collection
  {
    return JalaliDatePicker::weekdays($this->abbreviatingWeekdays);
  }
  

  /**
   * Getting the months of the year
   *
   * @return void
   */
  #[Computed()] 
  public function months() :Collection
  {
      return JalaliDatePicker::months();
  }


  /**
   * Retrieving the years between the specified minimum and maximum dates.
   *
   * @return Collection
   */
  #[Computed()] 
  public function years() :Collection
  {
      return JalaliDatePicker::years();
  }

    
  /**
   * Returns the name of the month for the selected date.
   *
   * @return string
   */
  #[Computed()]   
  public function getMonth() :string
  {
    return JalaliDatePicker::getMonth();
  }

  
  /**
   * Getting the year of the selected date
   *
   * @return int
   */
  #[Computed()]  
  public function getYear() :int
  {
    return JalaliDatePicker::getYear();
  }
}