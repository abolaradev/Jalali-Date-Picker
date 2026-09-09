<?php

namespace Abolaradev\JalaliDatePicker;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class JalaliDatePickerServiceProvider extends ServiceProvider
{
     /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge package configuration.
        $this->mergeConfigFrom(
            __DIR__.'/../config/jalali-date-picker.php',
            'jalali-date-picker'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        // Register the package views as a Livewire namespace
        Livewire::addNamespace(
            namespace: 'jalali-date-picker',
            viewPath: __DIR__ . '/../resources/views/livewire',
        );

        // Load the package views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'jalali-date-picker');

        // Publishing is only available when running from the console
        if (! $this->app->runningInConsole()) {
            return;
        }

        // Publish the package configuration file
        $this->publishes([
            __DIR__.'/../config/jalali-date-picker.php' => config_path('jalali-date-picker.php'),
        ], ['jalali-date-picker', 'jalali-date-picker-config']);

        // Publish the package assets
        $this->publishes([
            __DIR__.'/../resources/dist' => public_path('vendor/jalali-date-picker'),
        ], ['jalali-date-picker', 'jalali-date-picker-assets']);

    }
}
