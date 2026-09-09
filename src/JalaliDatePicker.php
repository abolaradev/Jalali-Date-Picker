<?php

namespace Abolaradev\JalaliDatePicker;

use DateTimeZone;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Morilog\Jalali\Jalalian;

class JalaliDatePicker 
{   
    /**
     * The currently selected Jalali date.
     */
    private $date;

    /**
     * The timezone used for date calculations.
     */
    private $timezone;

    /**
     * The earliest date that can be selected.
     */
    private $minDate;

    /**
     * The latest date that can be selected.
     */
    private $maxDate;



    public function __construct()
    {
        $defaultTimezone = config('jalali-date-picker.timezone');
        $this->timezone = new DateTimeZone($defaultTimezone);
    }
        

    /**
     * It returns the current Jalali date.
     *
     * @return Jalalian
     */
    public function now() :Jalalian
    {
        return Jalalian::now($this->timezone);
    }


    /**
     * Setting the Jalali date
     *
     * @param  mixed $year
     * @param  mixed $month
     * @param  mixed $day
     * @return void
     */
    public function setDate(int $year = null, int $month = null , int $day = null) :self
    {
        $year = (is_null($year)) ? $this->now()->getYear()
                                 : $year;

        $month = (is_null($month)) ? $this->now()->getMonth()
                                 : $month;

        $day = (is_null($day)) ? $this->now()->getDay()
                                 : $day;
     
        $this->date =$this->makeJalalianDate($year,$month,$day);
        
        return $this;
    }   
    

    /**
     * Get the selected date.
     *
     * @return Jalalian
     */
    public function getDate() :Jalalian
    {
        return $this->date;
    }
    

    /**
     * Create a new Jalalian instance from the given date components.
     *
     * @param  int $year The Jalali year.
     * @param  int $month The Jalali month.
     * @param  int $day The Jalali day.
     * @return Jalalian
     */
    private function makeJalalianDate(int $year,int $month,int $day): Jalalian 
    {
        return new Jalalian(
            year: $year,
            month: $month,
            day: $day,
            timezone: $this->timezone
        );
    }

    /**
     * Convert a Jalali date string into a Jalalian instance.
     *
     * The date string must be in the `YYYY/MM/DD` format.
     *
     * @param  string $date The Jalali date string.
     * @return Jalalian
     */
    private function getJalalianDateFromString(string $date): Jalalian
    {
        $dateToArray = explode('/', $date);

        $dateArrayWithKeys = [
            'year' => $dateToArray[0],
            'month' => $dateToArray[1],
            'day' => $dateToArray[2],
        ];

        return $this->makeJalalianDate(
            year: $dateArrayWithKeys['year'],
            month: $dateArrayWithKeys['month'],
            day: $dateArrayWithKeys['day']
        );
    }



    /**
     * Set the selectable date range for the DatePicker.
     *
     * The minimum and maximum dates can be provided as Carbon instances
     * or Jalali date strings in the `YYYY/MM/DD` format.
     *
     * @param  Carbon|string $minDate The earliest selectable date.
     * @param  Carbon|string $maxDate The latest selectable date.
     * @return self
     */
    public function dateRange( Carbon | string $minDate, Carbon | string $maxDate) :self
    {
        $this->minDate = $minDate instanceof Carbon ? Jalalian::forge($minDate)
                                                    : $this->getJalalianDateFromString($minDate);
        
        $this->maxDate = $maxDate instanceof Carbon ? Jalalian::forge($maxDate)
                                                    : $this->getJalalianDateFromString($maxDate);
        return $this;
    }


     /**
     * Getting the days of the week
     *
     * @param  bool $abbreviating Specifies that the days of the week be displayed in abbreviated form.
     * @return Collection
     */
    public function weekdays(bool $abbreviating) :Collection
    {
        $jalalian =$this->now()->getFirstDayOfWeek();

        $weekdays=collect(range(1,7))->map(function($value) use($jalalian,$abbreviating){

            $format = $abbreviating ? '%a' 
                                    : '%A';
            
            return $jalalian->addDays($value)
                            ->format($format);
        });

        $weekdays->prepend($weekdays->pop());

        return $weekdays;
    }


    /**
     * Retrieving the names of the Jalali calendar months
     *
     * @return Collection
     */
    public function months() :Collection
    {
        $jalalian=$this->now()->getFirstDayOfYear();
     
        $months = collect(range(1, 12))->mapWithKeys(function ($value,$key) use ($jalalian) {

            $month = ($key == 0) ? $jalalian
                                 : $jalalian->addMonths($key);

            return [
                $value => $month->format('%B')
            ];
        });

        return $months;
    }

    
    /**
     * Retrieving the years between the specified minimum and maximum dates.
     *
     * @return Collection
     */
    public function years() :Collection
    {
        $minYear=$this->minDate->getYear();
        $maxYear=$this->maxDate->getYear();
        $spanYears=$maxYear - $minYear;

        $years = collect(range(0,$spanYears))->map(function($value) use($minYear){
            return $value + $minYear;
        })->reverse();

        return $years;
    }

    
    /**
     * Get the name of the month for the selected date
     *
     * @return string
     */
    public function getMonth() 
    {
        return $this->months()
                    ->get(
                        $this->getDate()->getMonth()
                    );
     
    }
        
    
    /**
     * Extracting the year from the selected date
     *
     * @return int
     */
    public function getYear() :int
    {
        return $this->getDate()
                    ->getYear();
    }
    
    
    /**
     * It is checked whether the selected date is today or not.
     *
     * @param  string $date Selected date
     * @return bool
     */
    public function isToday(string $date) :bool
    {
        return $this->getJalalianDateFromString($date)
                    ->isToday();
                   
    }
    

    /**
     * Get the days from the previous month that are displayed
     * at the beginning of the current month's calendar grid.
     *
     * @return Collection
     */
    private function getPreviousMonthDays(): Collection
    {
        $days = collect([]);

        $dayOfWeek = $this->getDate()
                        ->getFirstDayOfMonth()
                        ->getDayOfWeek();

        $endOfLastMonth = $this->getDate()
                            ->subMonths()
                            ->getEndDayOfMonth();

        for ($day = 0; $day < $dayOfWeek; $day++) {
            $days->add(
                $endOfLastMonth->subDays($day)->format('Y/m/d')
            );
        }

        $days = $days->reverse();

        return $days;
    }


    /**
     * Get all days of the current month.
     *
     * @return Collection
     */
    private function getDaysInMonth(): Collection
    {
        $days = collect([]);

        $monthsDays = $this->getDate()
                        ->getMonthDays();

        for ($day = 0; $day < $monthsDays; $day++) {
            $days->add(
                $this->getDate()
                    ->getFirstDayOfMonth()
                    ->addDays($day)
                    ->format('Y/m/d')
            );
        }

        return $days;
    }


    /**
     * Get the days from the next month that are displayed
     * at the end of the current month's calendar grid.
     *
     * The grid contains either 35 or 42 days depending
     * on the number of days already occupied by the current month.
     *
     * @return Collection
     */
    private function getNextMonthDays(): Collection
    {
        $days = collect([]);

        $daysCount = $this->getPreviousMonthDays()
                        ->merge($this->getDaysInMonth())
                        ->count();

        $firstDayNextMonth = $this->getDate()
                                ->addMonths()
                                ->getFirstDayOfMonth();

        $remainingDays = match (true) {
            $daysCount <= 35 => 35 - $daysCount,
            $daysCount > 35  => 42 - $daysCount
        };

        for ($i = $firstDayNextMonth->getDay(); $i <= $remainingDays; $i++) {
            $days->add(
                $firstDayNextMonth
                    ->addDays($i - 1)
                    ->format('Y/m/d')
            );
        }

        return $days;
    }


    /**
     * Build the complete calendar grid layout.
     *
     * The grid contains the remaining days from the previous month,
     * all days of the current month, and the required days from the
     * next month to complete the calendar rows.
     *
     * @return Collection
     */
    public function calendarGridLayout(): Collection
    {
        $grid = collect([
            'previousMonthDays' => $this->getPreviousMonthDays(),
            'daysInMonth' => $this->getDaysInMonth(),
            'nextMonthDays' => $this->getNextMonthDays()
        ]);

        return $grid;
    }
}
