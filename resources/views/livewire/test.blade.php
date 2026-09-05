<?php

use Abolaradev\JalaliDatePicker\Facades\JalaliDatePicker;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('jalali-date-picker::layouts.app')] class extends Component
{
  
};
?>

<div class=" h-screen flex items-center justify-center bg-stone-500">
     <livewire:jalali-date-picker::calendar/>
</div>