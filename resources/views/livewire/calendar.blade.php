<?php

use Abolaradev\JalaliDatePicker\Facades\JalaliDatePicker;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Modelable;
use Livewire\Component;

new #[Layout('jalali-date-picker::layouts.app')] class extends Component
{
   /**
   * Determines whether the calendar should close after selecting a date.
   */
   public bool $autoClose=true;

   /**
    * Specifies that the calendar closes when clicking outside its area.
    */
   public bool $outsideClose = true ; 

   /**
    * Getting the days of the week
    */
   public array $weekdays;

   /**
    * Get the selected date
    */
   #[Modelable]
    public mixed $selectedDate;


    public  $today;


    public $year;
    public $month;
    public $day;

    public function mount()
    {
        $this->today=JalaliDatePicker::today();
        $this->weekdays = JalaliDatePicker::weekdays();
    }

    public function render()
    {

       return $this->view([
          'grid'=> JalaliDatePicker::setDate($this->year,$this->month,$this->day)
                                   ->calendarGridLayout()
       ]);
    }


};
?>

<div class="min-w-80" x-data="jalaliDatePicker" x-cloak>
        {{-- input  --}}
        <div class="relative">
          <input x-bind="input" 
                 class="p-2 border border-stone-300 outline-none caret-transparent w-full z-50">
             <button class=" absolute inset-y-0 right-0 px-2 cursor-pointer z-90 h-full" x-bind="resetDateButton">
             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
               <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
               </svg>
             </button>
        </div>

         {{-- calendar  --}}
           <div class="flex flex-col justify-between  text-center  bg-fuchsia-50 border-stone-300 w-fit p-2 rounded-b-2xl gap-2 select-none " x-bind="calendar" x-cloak >
             <div class="flex flex-col bg-fuchsia-700 text-fuchsia-100">
              
                {{-- weekdays --}}
                 <div class="grid grid-cols-7 text-xs bg-fuchsia-700">
                    @foreach ($weekdays as $weekday)
                         <span
                              class="p-2 font-semibold"
                              wire:key="weekday-{{ $loop->iteration }}">
                              {{ $weekday }}
                         </span>
                    @endforeach
                </div>
            </div>  

             {{-- calender layout  --}}
              <div class="grid grid-cols-7 gap-1 text-sm">

            {{-- Days of the previous month --}}
               @foreach ($grid->last_month as $day)
                  <button
                         class="h-9 rounded-md px-3 bg-fuchsia-200 opacity-50 text-fuchsia-700"
                         x-bind="unselectableDays"
                         wire:key="{{ $day }}"
                         value="{{ $day }}"></button>
               @endforeach

               {{-- The days of this month --}}
                @foreach ($grid->current_month as $day)
                    <button
                            class="h-9 rounded-md px-3 cursor-pointer"
                            x-bind="selectableDays"
                            wire:key="{{ $day }}"
                            value="{{ $day }}"></button>
               @endforeach

              {{-- Days of the next month --}}
               @foreach ($grid->next_month as $day)
                  <button
                         class="h-9 rounded-md px-3 bg-fuchsia-200 opacity-50 text-fuchsia-700"
                         x-bind="unselectableDays"
                         wire:key="{{ $day }}"
                         value="{{ $day }}"></button>
               @endforeach
            </div>
         </div>
</div>

@assets
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<script defer src="{{ package_asset('js/JalaliDatePicker.js') }}"></script>
@endassets