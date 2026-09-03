<?php

use Illuminate\Support\Facades\Artisan;
use Workbench\App\Abolaradev\Asset;
use Workbench\App\Abolaradev\Livewire;
use Workbench\App\Abolaradev\Helper;


Artisan::command('livewire:layout {layout?}',function(string $layout = ''){
    $layout = empty($layout) ? 'app'
                             : $layout;          
  
    $makeLayout=Livewire::setPath()
                        ->atDirectory('layouts')
                        ->component($layout)
                        ->isLayout()
                        ->create();
    
    Livewire::consoleOutput(
        result: $makeLayout,
        success: 'Livewire layout Created',
        fail: 'Livewire layout is exist'
    );

})->purpose('Create a new Livewire layout');


Artisan::command('make:livewire {component}',function(string $component){

    $parts = explode('.', $component);
    $name = array_pop($parts);
    $path = implode('.', $parts);
  
    
    $makeComponent=Livewire::setPath()
                        ->atDirectory("livewire\\".$path)
                        ->component($name)
                        ->create();
    
    Livewire::consoleOutput(
        result: $makeComponent,
        success: 'Livewire Component Created',
        fail: 'Livewire Component is exist'
    );

})->purpose('Create a new Livewire component');


Artisan::command('livewire:make {component}', function (string $component){
    $this->call('make:livewire',[
        'component' => $component
    ]);
})->purpose('Create a new Livewire component');


Artisan::command('make:component {component}',function(string $component){

    $parts = explode('.', $component);
    $name = array_pop($parts);
    $path = implode('.', $parts);
  
    
    $makeComponent=Livewire::setPath()
                        ->atDirectory("components\\".$path)
                        ->component($name)
                        ->isBladeComponent()
                        ->create();
    
    Livewire::consoleOutput(
        result: $makeComponent,
        success: 'Blade Component Created',
        fail: 'Blade Component is exist'
    );

})->purpose('Create a new Blade component');

Artisan::command('make:helper',function(){
    Helper::make()
          ->composer()
          ->dumpAutoLoad();
})->purpose('Create helper file');

Artisan::command('make:assets {--except=?}', function () {

    $except = explode(',', $this->option('except'));

    Asset::make($except)
        ->createHelperAsset();

})->purpose('Create package assets');


Artisan::command('assets:link', function () {

    Asset::link();

})->purpose('Link package assets to the public directory');