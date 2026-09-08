<?php

namespace Abolaradev\JalaliDatePicker;

use DateTimeZone;
use Illuminate\Support\Arr;
use Morilog\Jalali\Jalalian;

class JalaliDatePicker 
{   
    private $date;

    private $timezone;

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
     
        $this->date = new Jalalian(
           year: $year ,
           month: $month ,
           day: $day , 
           timezone: $this->timezone
        );
        
        return $this;
    }   

    public function getDate()
    {
        return $this->date;
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
     * It takes a Jalali date in string format and compares it with today's Jalali date.
     *
     * @param  string $date
     * @return bool
     */
    public function isTodayFromDateString(string $date) :bool
    {
        $dateToArray = explode('/',$date);
        $dateArrayWithKeys = [
            'year' => $dateToArray[0],
            'month' => $dateToArray[1],
            'day' => $dateToArray[2],
        ];

        $jalalianDate = new Jalalian(
            year: $dateArrayWithKeys['year'],
            month: $dateArrayWithKeys['month'],
            day: $dateArrayWithKeys['day'] , 
            timezone: $this->timezone
        );
        
        return $jalalianDate->isToday();
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
     * It indicates which day of the first week of the month the first day of the month falls on.
     *
     * @return int
     */
    public function firstDayOfMonthWeekday() :int
    {
       return $this->getDate()
                   ->getFirstDayOfMonth()
                   ->getDayOfWeek();
       
    }

    /**
     * It returns the last days of the previous month.
     *
     * @return self
     */
    public function lastDaysPreviousMonth() :array
    {
        $days=[];
        $endOfLastMonth=$this->getDate()
                             ->subMonths()
                             ->getEndDayOfMonth();

        for ($i=0 ; $i < $this->firstDayOfMonthWeekday() ; $i++) { 
             $days[]=$endOfLastMonth->subDays($i)->format('Y/m/d');
        }

        $days = array_reverse($days);

        return $days;
    }


    /**
     * Returns the days of the current month.
     *
     * @return array
     */
    public function daysInMonth() :array
    {
        $days=[];
        $countOfDaysInMonth=$this->getDate()
                                 ->getMonthDays();

        for ($i=0; $i < $countOfDaysInMonth ; $i++) { 
            $days[]=$this->getDate()->getFirstDayOfMonth()->addDays($i)->format('Y/m/d');
        }
        
        return $days;
    }
    
    
    /**
     * It returns the first days of the next month.
     *
     * @return array
     */
    public function firstDaysNextMonth() :array
    {
        $firstDay= $this->getDate()->addMonths()->getFirstDayOfMonth();

        $countOfGrids= count($this->lastDaysPreviousMonth()) + count($this->daysInMonth());

        $remainingGrades = match (true) {
            $countOfGrids <= 35 => 35 - $countOfGrids,
            $countOfGrids > 35  => 42 - $countOfGrids
        };

        $days=[];
        for ($i= $firstDay->getDay() ; $i <= $remainingGrades ; $i++) { 
            $days[]=$firstDay->addDays($i-1)->format('Y/m/d');
        }

        return $days;
    }
    

    /**
     * It forms the calendar grid.
     *
     * @return void
     */
    public function calendarGridLayout() :object
    {
        $grid= [
            'last_month' => $this->lastDaysPreviousMonth(),
            'current_month' => $this->daysInMonth(),
            'next_month' => $this->firstDaysNextMonth()
        ];
            
        return (object) $grid;
    }
}
