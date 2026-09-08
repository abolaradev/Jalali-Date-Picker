<?php

namespace Abolaradev\JalaliDatePicker;

use DateTimeZone;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Morilog\Jalali\Jalalian;

class JalaliDatePicker 
{   
    private $date;

    private $timezone;

    private $minDate;

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

    public function getDate()
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
     * Retrieving the names of the Jalali calendar months
     *
     * @return array
     */
    public function months() :array
    {
        $jalalian=$this->now()->getFirstDayOfYear();
        $months=[];

        for ($i=0; $i < 12; $i++) { 
            $months[$i+1] = ($i == 0) ? $jalalian
                                        : $jalalian->addMonths($i);
        }
        
         $months=Arr::map($months,function($month){
            return $month->format('%B');
        });

        return $months;
    }

      public function years() :array
    {
        $minYear=$this->minDate->getYear();
        $maxYear=$this->maxDate->getYear();
        $spanYears=$maxYear - $minYear;

        $years = [];

        for ($i=0; $i <= $spanYears ; $i++) { 
            $years[]= $minYear + $i;
        }

        return array_reverse($years);
    }


    public function month()
    {
        $months=$this->months();
        return $months[$this->getDate()->getMonth()];
    }
    
    public function year() :int
    {
        return $this->getDate()->getYear();
    }
    
    /**
     * Getting the days of the week
     *
     * @param  bool $abbreviating
     * @return void
     */
    public function weekdays(bool $abbreviating) :array
    {
        $jalalian =$this->now()->getFirstDayOfWeek();
        $weekdays=[];

        for ($i=0; $i < 7; $i++) { 
            $weekdays[] = ($i == 0) ? $jalalian
                                    : $jalalian->subDays($i);
        }
        $format = $abbreviating ? '%a' 
                                : '%A';
        $weekdays=array_reverse(Arr::map($weekdays,function($day) use($format){
            return $day->format($format);
        }));

        array_unshift($weekdays,array_pop($weekdays));

        return $weekdays;
    }
    
    /**
     * It returns today's Jalali date as a simple string, based on the specified format.
     *
     * @return string
     */
    public function today() :string
    {
        return $this->now()
                    ->format('Y/m/d');
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
