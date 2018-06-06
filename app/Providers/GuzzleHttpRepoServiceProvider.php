<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GuzzleHttpRepoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Interfaces\GuzzleHttpInterface', 'App\Repositories\GuzzleHttpRepository');

    }
}
