<?php

use Abolaradev\JalaliDatePicker\Facades\JalaliDatePicker;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('jalali-date-picker::layouts.app')] class extends Component
{
    public $date;
  
};
?>

<div class=" min-h-screen flex items-center justify-center bg-stone-500">
     
     <livewire:jalali-date-picker::calendar wire:model="date" sasd/>
     <button wire:click="$refresh">click</button>

</div>