<?php

use Abolaradev\JalaliDatePicker\Facades\JalaliDatePicker;
use Abolaradev\JalaliDatePicker\Traits\WithJalaliDatePicker;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Modelable;
use Livewire\Component;

new #[Layout('jalali-date-picker::layouts.app')] class extends Component
{
   use WithJalaliDatePicker;

   /**
    * Get the selected date
    */
   #[Modelable]
   public $selectedDate;

   public $month;

   public $year;

   public function render()
   {   
      return $this->view([
         'grid'=> JalaliDatePicker::setDate($this->year,$this->month)
                                  ->dateRange($this->minDate,$this->maxDate)
                                  ->calendarGridLayout()
      ]);
   }
};
?>

<div class=" w-auto mb-80" x-data="jalaliDatePicker" x-cloak>
        {{-- input  --}}
        <div class="relative" x-bind="input">
          <input x-bind="input.input" 
                 class="p-2 border border-stone-300 outline-none caret-transparent w-full z-50">
             <button class=" absolute inset-y-0 right-0 px-2 cursor-pointer z-90 h-full" x-bind="input.reset">
             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
               <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
               </svg>
             </button>
        </div>

         {{-- calendar  --}}
           <div class="flex flex-col justify-between  text-center bg-neutral-50 border-stone-300 pb-4  rounded-b-2xl gap-2 select-none  " x-bind="calendar" >
             <div class="flex flex-col px-3 gap-2 bg-jalali-700 text-jalali-100">
              
               {{-- year & month  --}}
               <div class="flex justify-center gap-2 border-b border-jalali-100  py-3 text-xl"  >
                  <button type="button" class=" cursor-pointer" data-picker="months" x-bind="calendar.dateNavigationPanel.picker">{{ $this->getMonth }}</button>
                  <button type="button" class=" cursor-pointer" data-picker="years" x-bind="calendar.dateNavigationPanel.picker">{{ $this->getYear}}</button>
               </div>

                {{-- weekdays --}}
                 <div @class([
                        "grid grid-cols-7 py-2 gap-1 font-semibold", 
                        'text-sm' => $abbreviatingWeekdays,
                        'text-xs' => !$abbreviatingWeekdays
                     ]) >
                    @foreach ($this->weekdays as $weekday)
                         <span
                              wire:key="weekday-{{ $loop->iteration }}">
                              {{ $weekday }}
                         </span>
                    @endforeach
                </div>
            </div>  

            

             {{-- calender layout  --}}
              <div class="grid grid-cols-7 gap-1 text-sm px-3">

            {{-- Days of the previous month --}}
               @foreach ($grid->get('previousMonthDays') as $day)
                  <button x-bind="calendar.unselectableDays"
                          wire:key="{{ $day }}"
                          value="{{ $day }}"></button>
               @endforeach

               {{-- The days of this month --}}
                @foreach ($grid->get('daysInMonth') as $day)
                    <button 
                           @if ($this->isToday($day))
                           data-istoday="true"
                           @endif

                           @if ($this->isGreaterThanMaxDate($day) || $this->isLessThanMinDate($day))
                           x-bind="calendar.unselectableDays"
                           @else
                           x-bind="calendar.selectableDays"
                           @endif
                           
                            wire:key="{{ $day }}"
                            value="{{ $day }}"></button>
               @endforeach

              {{-- Days of the next month --}}
               @foreach ($grid->get('nextMonthDays') as $day)
                  <button x-bind="calendar.unselectableDays"
                          wire:key="{{ $day }}"
                          value="{{ $day }}"></button>
               @endforeach
           </div>

             {{-- year-month picker box  --}}
            <div class=" absolute h-full w-full inset-y-0 right-0 flex flex-col gap-4 p-3 rounded-b-2xl text-sm font-semibold  bg-jalali-700 text-jalali-100" x-bind="calendar.dateNavigationPanel">
              
                {{-- header  --}}
                <div class="flex justify-between border-b py-2 border-jalali-100">
                  <div class=" flex items-baseline gap-2">
                     <h4 class="text-xl" x-bind="calendar.dateNavigationPanel.title"></h4>

                     <span wire:loading wire:target="month,year"  class=" size-4 animate-spin rounded-full border-2 border-jalali-200 border-t-jalali-600"></span>

                  </div>
                   <button class=" self-end cursor-pointer border border-jalali-100 rounded-md p-1" x-bind="calendar.dateNavigationPanel.close" >
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                   </button>
                </div>

               {{-- month picker  --}}
                     <div class="grid grid-cols-3 gap-2 w-full h-full overflow-y-auto scroll-smooth px-3 items-stretch" x-show="showPicker.month" >
                        @foreach ($this->months as $key => $month)
                           <button 
                                 x-bind="calendar.dateNavigationPanel.button"
                                 wire:key="month-{{ $key }}"
                                 @if ($this->getMonth == $month)
                                 data-selected="true"
                                 @endif
                                 wire:click="$set('month','{{ $key }}')"
                                 value="{{  $month }}"></button>
                        @endforeach
                     </div>
   

                  {{-- year picker  --}}
                <div class="grid grid-cols-4 gap-3 w-full h-full overflow-y-auto scroll-smooth px-3 items-stretch" x-show="showPicker.year" >
                     @foreach ($this->years as  $year)
                        <button 
                              x-bind="calendar.dateNavigationPanel.button"
                              @if ($this->getYear == $year)
                              data-selected="true"
                              @endif
                              wire:key="year-{{ $year }}"
                              wire:click="$set('year','{{ $year }}')"
                              value="{{  $year }}"></button>
                     @endforeach
                  </div>            
           </div>
      </div>
</div>

@assets
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<script defer src="{{ package_asset('js/JalaliDatePicker.js') }}"></script>
@endassets