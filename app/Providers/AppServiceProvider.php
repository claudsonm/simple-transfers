<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(
            \Faker\Generator::class,
            fn () => \Faker\Factory::create('pt_BR')
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    }
}
