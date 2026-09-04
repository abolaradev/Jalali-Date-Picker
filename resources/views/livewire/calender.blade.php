<?php

use Abolaradev\JalaliDatePicker\Facades\JalaliDatePicker;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('livewire-jalali-date-picker::layouts.app')] class extends Component
{
    public $grid;
    public $selectedDate;

    public function mount()
    {
        $this->grid= JalaliDatePicker::calendarGridLayout();
    }

    public function getDate($date)
    {
       $this->selectedDate=$date;
    }


};
?>

<div class=" h-screen flex items-center justify-center bg-stone-800">
     {{-- <input type="text" class=" p-2 border border-stone-300"> --}}
     <div class="flex flex-col justify-between  text-center  bg-fuchsia-50 border-stone-300 p-2 rounded-b-2xl gap-2 relative select-none" dir="rtl">

          <div class="flex flex-col bg-fuchsia-700 text-fuchsia-100">
               <div class=" flex justify-center items-center gap-2 border-b py-3 border-fuchsia-200 relative">
                    <div class="cursor-pointer">
                         <div class=" tracking-widest"></div>
                    </div>
                    <div class=""></div>
               </div>
                 <div class="grid grid-cols-7 text-xs bg-fuchsia-700">
                    @foreach ($grid['weekdays'] as $weekday)
                         <span
                              class="p-2 font-semibold"
                              wire:key="weekday-{{ $loop->iteration }}">
                              {{ $weekday }}
                         </span>
                    @endforeach
               </div>

               {{ $selectedDate }}
          </div>

            <div class="grid grid-cols-7 gap-1 text-sm">
               @foreach ($grid['last-month'] as $day)
                  <button
                         type="button"
                         class="h-9 rounded-md px-3 bg-fuchsia-200 opacity-50 text-fuchsia-700"
                         wire:key="{{ $day }}"
                         @disabled(true)>
                         {{ getDay($day) }}
                    </button>
               @endforeach

                @foreach ($grid['current-month'] as $day)
                    <button
                         type="button"
                         {{ $attributes->class([
                              'h-9 rounded-md px-3 cursor-pointer',
                              'bg-fuchsia-700 text-fuchsia-100 font-semibold transition-colors duration-300 ' => $selectedDate == $day,
                              'bg-fuchsia-200 text-fuchsia-700 hover:bg-fuchsia-300' => $selectedDate != $day,
                              'border border-fuchsia-700' => isToday($day)

                         ]) }}
                            wire:key="{{ $day }}"
                            wire:click.throttle.500ms="getDate('{{ $day }}')">
                      {{ getDay($day) }}
                    </button>
               @endforeach

                @foreach ($grid['next-month'] as $day)
                  <button
                         type="button"
                         class="h-9 rounded-md px-3 bg-fuchsia-200 opacity-50 text-fuchsia-700"
                         wire:key="{{ $day }}"
                         @disabled(true)>
                         {{ getDay($day) }}
                    </button>
               @endforeach 
          </div> 

          @unless (empty($selectedDate))
               <button class="px-3 py-1.5 rounded-lg text-sm self-end bg-fuchsia-700 text-fuchsia-100 "
                       wire:click="$set('selectedDate','')">
                    پاک کردن
               </button>              
          @endunless

          {{-- <div class="bg-fuchsia-700 text-fuchsia-100 absolute w-full h-full inset-y-0 right-0 flex flex-col gap-3">
               <div class="overflow-y-auto grid grid-cols-3 h-full p-4 gap-1">
                    @foreach ($grid['months'] as $month)
                         <button type="button" 
                                 class="min-h-15 cursor-pointer rounded-md border border-fuchsia-200 flex items-center justify-center hover:bg-fuchsia-600 font-semibold">
                              {{ $month }}
                         </button>
                    @endforeach
               </div>
              </div> --}}
     </div>
</div>

@assets
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
@endassets