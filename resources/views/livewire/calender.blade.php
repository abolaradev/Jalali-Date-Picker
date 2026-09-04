<?php

use Abolaradev\JalaliDatePicker\Facades\JalaliDatePicker;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('livewire-jalali-date-picker::layouts.app')] class extends Component
{
    public $grid;
    public $today;
    public $weekdays;

    public function mount()
    {
        $this->grid=JalaliDatePicker::calenderGridLayout();
        $this->today=JalaliDatePicker::dayOfMonth();
        $this->weekdays=JalaliDatePicker::daysOfWeekAbbreviations();

    
    }
};
?>

<div class=" h-screen flex items-center justify-center">
     <div class="flex flex-col justify-around  text-center text-sm bg-fuchsia-50 border-stone-300 p-4 rounded-b-2xl gap-2" dir="rtl">

           <div class="grid grid-cols-7 text-xs">

               @foreach ($weekdays as $weekday)
                    <span
                         class="p-2 font-semibold bg-fuchsia-700 text-fuchsia-100"
                         wire:key="weekday-{{ $loop->iteration }}">
                         {{ $weekday }}
                     </span>
               @endforeach

          </div>

           <div class="grid grid-cols-7 gap-2">
               @foreach ($grid['last-month'] as $day)
                  <button
                         type="button"
                         class="h-9 rounded-lg px-3 bg-fuchsia-200 opacity-50 text-fuchsia-700"
                         wire:key="last-month-day-{{ $day }}"
                         @disabled(true)>
                         {{ $day }}
                    </button>
               @endforeach

               @foreach ($grid['current-month'] as $day)
                    <button
                         type="button"
                         {{ $attributes->class([
                              'h-9 rounded-lg px-3 cursor-pointer',
                              'bg-fuchsia-700 text-fuchsia-100 font-semibold' => $day == $today,
                              'bg-fuchsia-200 text-fuchsia-700' => $day != $today,
                         ]) }}
                            wire:key="current-month-day-{{ $day }}">
                         {{ $day }}
                    </button>
               @endforeach

                @foreach ($grid['next-month'] as $day)
                  <button
                         type="button"
                         class="h-9 rounded-lg px-3 bg-fuchsia-200 opacity-50 text-fuchsia-700"
                         wire:key="next-month-day-{{ $day }}"
                         @disabled(true)>
                         {{ $day }}
                    </button>
               @endforeach
          </div>
     </div>
</div>

@assets
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
@endassets