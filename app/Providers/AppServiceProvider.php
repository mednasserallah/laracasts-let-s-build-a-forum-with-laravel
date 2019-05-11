<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Debugbar::disable();

        \View::composer('*', function ($view) {
            $channels = \Cache::rememberForever('channels', function () {
                return \App\Channel::orderBy('name')->get();
            });

            $view->with('channels', $channels);
        });
    }
}
