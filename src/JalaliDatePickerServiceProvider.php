<?php

namespace Abolaradev\JalaliDatePicker;

use Illuminate\Support\ServiceProvider;
use Abolaradev\JalaliDatePicker\Commands\JalaliDatePickerCommand;
use Livewire\Livewire;
use Illuminate\Support\Facades\Blade;

class JalaliDatePickerServiceProvider extends ServiceProvider
{
     /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge package configuration.
        $this->mergeConfigFrom(
            __DIR__.'/../config/livewire-jalali-date-picker.php',
            'livewire-jalali-date-picker'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Register the package views as a Livewire namespace
        Livewire::addNamespace(
            namespace: 'livewire-jalali-date-picker',
            viewPath: __DIR__ . '/../resources/views/livewire',
        );

        // Register the package Blade components namespace
        Blade::componentNamespace(__DIR__ . '/../resources/views/components','livewire-jalali-date-picker');

        // Load the package views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livewire-jalali-date-picker');

        // Publishing is only available when running from the console
        if (! $this->app->runningInConsole()) {
            return;
        }

        // Publish the package configuration file
        $this->publishes([
            __DIR__.'/../config/livewire-jalali-date-picker.php' => config_path('livewire-jalali-date-picker.php'),
        ], ['livewire-jalali-date-picker', 'livewire-jalali-date-picker-config']);

        // Publish the package views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/livewire-jalali-date-picker'),
        ], ['livewire-jalali-date-picker', 'livewire-jalali-date-picker-views']);

        // Publish the package assets
        $this->publishes([
            __DIR__.'/../resources/dist' => public_path('vendor/livewire-jalali-date-picker'),
        ], ['livewire-jalali-date-picker', 'livewire-jalali-date-picker-assets']);

    }
}
