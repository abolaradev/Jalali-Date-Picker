<?php

namespace Abolaradev\JalaliDatePicker;

use DateTimeZone;
use Illuminate\Support\Arr;
use Morilog\Jalali\Jalalian;

class JalaliDatePicker 
{   
    private $date;
    

    
    public function now()
    {
        return Jalalian::now();
    }

    /**
     * Setting the Jalali date
     *
     * @param  mixed $year
     * @param  mixed $month
     * @param  mixed $day
     * @return void
     */
    // public function setDate(int $year = null, int $month = null , int $day = null) :self
    // {
    //     $year = (is_null($year)) ? $this->now()->getYear()
    //                              : $year;

    //     $month = (is_null($month)) ? $this->now()->getMonth()
    //                              : $month;

    //     $day = (is_null($day)) ? $this->now()->getDay()
    //                              : $day;
     
    //     $this->date = new Jalalian($year , $month , $day);

    //     return $this;
    // }   
    
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

    // public function month()
    // {
    //     $months=$this->months();
    //     return $months[$this->getDate()->getMonth()];
    // }
    
    // public function year()
    // {
    //     return $this->getDate()->getYear();
    // }

    /**
     * Getting the days of the week
     *
     * @return array
     */
    public function weekdays() 
    {
        $jalalian =$this->now()->getFirstDayOfWeek();
        $weekdays=[];

        for ($i=0; $i < 7; $i++) { 
            $weekdays[] = ($i == 0) ? $jalalian
                                    : $jalalian->subDays($i);
        }
        
        $weekdays=array_reverse(Arr::map($weekdays,function($day){
            return $day->format('%a');
        }));

        array_unshift($weekdays,array_pop($weekdays));

        return $weekdays;
    }

      /**
     * It takes a Jalali date in string format and returns the day of that date.
     *
     * @param  string $date
     * @return int
     */
    public function getDayFromDateString(string $date) :int
    {
        $day = explode('/',$date);

        return (int) end($day);
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

        $jalalianDate = new Jalalian($dateArrayWithKeys['year'],$dateArrayWithKeys['month'],$dateArrayWithKeys['day'] , timezone: new DateTimeZone('ASIA/TEHRAN'));
        
        return $jalalianDate->isToday();
    }

    
    /**
     * It indicates which day of the first week of the month the first day of the month falls on.
     *
     * @return int
     */
    public function firstDayOfMonthWeekday() :int
    {
       return $this->now()
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
        $endOfLastMonth=$this->now()
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
        $countOfDaysInMonth=$this->now()
                                 ->getMonthDays();

        for ($i=0; $i < $countOfDaysInMonth ; $i++) { 
            $days[]=$this->now()->getFirstDayOfMonth()->addDays($i)->format('Y/m/d');
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
        $firstDay= $this->now()->addMonths()->getFirstDayOfMonth();

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
    public function calendarGridLayout() :array
    {
        $grid= [
            'weekdays'=> $this->weekdays(),
            'months' => $this->months(),
            'last-month' => $this->lastDaysPreviousMonth(),
            'current-month' => $this->daysInMonth(),
            'next-month' => $this->firstDaysNextMonth()
        ];
            
        return $grid;
    }
}
