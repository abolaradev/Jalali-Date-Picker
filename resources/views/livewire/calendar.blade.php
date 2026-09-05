<?php

use Abolaradev\JalaliDatePicker\Facades\JalaliDatePicker;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('jalali-date-picker::layouts.app')] class extends Component
{
    public array $weekdays;

    public $selectedDate;

    public $year;
    public $month;
    public $day;

    public function mount()
    {
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

<div class="min-w-80" x-data="jalaliDatePicker">
        {{-- input  --}}
      
         <input x-bind="input" 
                class="p-2 border border-stone-300 outline-none caret-transparent w-full"
                wire:model.live='selectedDate'>
   
         {{-- calendar  --}}
           <div class="flex flex-col justify-between  text-center  bg-fuchsia-50 border-stone-300 p-1 rounded-b-2xl gap-2 select-none " x-bind="calendar" x-cloak >
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
                         wire:key="{{ $day }}"
                         x-bind="unselectableDays"
                         value="{{ $day }}">
                    </button>
               @endforeach

               {{-- The days of this month --}}
                @foreach ($grid->current_month as $day)
                    <button
                         {{ $attributes->class([
                              'h-9 rounded-md px-3 cursor-pointer',
                              'bg-fuchsia-700 text-fuchsia-100 font-semibold transition-colors duration-300 ' => $selectedDate == $day,
                              'bg-fuchsia-200 text-fuchsia-700 hover:bg-fuchsia-300' => $selectedDate != $day,
                              'border border-fuchsia-700' => isToday($day)

                         ]) }}
                            x-bind="selectableDays"
                            wire:key="{{ $day }}"
                            value="{{ $day }}"></button>
               @endforeach

              {{-- Days of the next month --}}
               @foreach ($grid->next_month as $day)
                  <button
                         class="h-9 rounded-md px-3 bg-fuchsia-200 opacity-50 text-fuchsia-700"
                         wire:key="{{ $day }}"
                         x-bind="unselectableDays"
                         value="{{ $day }}">
                    </button>
               @endforeach



            </div>
         </div>
</div>

@assets
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<script defer src="{{ package_asset('js/JalaliDatePicker.js') }}"></script>
@endassets