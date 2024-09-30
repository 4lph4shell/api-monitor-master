<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * This method is called when the service provider is registered
     * via the register method of the application. It is used to
     * set up any services that need to be bootstrapped.
     *
     * @return void
     */
    public function boot()
    {
        // This method is called after all service providers have been
        // registered, so it's a good place to bind services to the
        // IoC container.
    }

    /**
     * Register any application services.
     *
     * This method is called when the service provider is registered via
     * the register method of the application. It is used to bind services
     * to the container.
     *
     * @return void
     */
    public function register()
    {
        // Bind the TelegramChannel class to the IoC container
        $this->app->bind('App\Channels\TelegramChannel', function ($app) {
            return new TelegramChannel($app['config']['telegram']);
        });
    }
}
