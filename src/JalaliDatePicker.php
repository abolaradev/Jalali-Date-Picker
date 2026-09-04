<?php

namespace Abolaradev\JalaliDatePicker;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Morilog\Jalali\Jalalian;

class JalaliDatePicker 
{   

    /**
     * Obtaining the Jalali date
     *
     * @return Jalalian
     */
    public function now() :Jalalian
    {
        return Jalalian::now()->addMonths();
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
            $months[] = ($i == 0) ? $jalalian
                                        : $jalalian->addMonths($i);
        }
        
         $months=Arr::map($months,function($month){
            return $month->format('%B');
        });

        return $months;
    }
    

    /**
     * Getting the days of the week
     *
     * @return array
     */
    public function daysOfWeek() 
    {
        $jalalian =$this->now()->getFirstDayOfWeek();
        $weekdays=[];

        for ($i=0; $i < 7; $i++) { 
            $weekdays[] = ($i == 0) ? $jalalian
                                    : $jalalian->subDays($i);
        }
        
        $weekdays=array_reverse(Arr::map($weekdays,function($day){
            return str_replace("\u{200C}", ' ', $day->format('%A'));
        }));

        array_unshift($weekdays,array_pop($weekdays));

        return $weekdays;
    }


    /**
     * Getting the abbreviation for the days of the week
     *
     * @return array
     */
    public function daysOfWeekAbbreviations() :array
    {
        $week=Arr::map($this->daysOfWeek(),function($day){ 
            return mb_substr($day,0,1);
        });
        
        return $week;
    }
       

    /**
     * Getting the day number of the month
     *
     * @return int
     */
    public function dayOfMonth() :int
    {
        return $this->now()
                    ->getDay();
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
     * It indicates which day of the week the first day of the following month falls on.
     *
     * @return int
     */
    public function nextMonthFirstDay() :int
    {
        return $this->now()
                    ->addMonths()
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
        $endOfMonth=$this->now()
                         ->subMonths()
                         ->getEndDayOfMonth();

        for ($i=0 ; $i < $this->firstDayOfMonthWeekday() ; $i++) { 
             $days[]=$endOfMonth->subDays($i)->getDay();
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

        for ($i=1; $i <= $countOfDaysInMonth ; $i++) { 
            $days[]=$i;
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
        $firstDay= $this->now()->addMonths()->getFirstDayOfMonth()->getDay();
        $days=[];
        for ($i= $firstDay ; $i < $this->nextMonthFirstDay() ; $i++) { 
            $days[]=$i;
        }

        return $days;
    }
    

    /**
     * It forms the calendar grid.
     *
     * @return void
     */
    public function calenderGridLayout()
    {
        $grid= [
            'last-month' => $this->lastDaysPreviousMonth(),
            'current-month' => $this->daysInMonth(),
            'next-month' => $this->firstDaysNextMonth(),
        ];
            
        return $grid;
    }
    
}
