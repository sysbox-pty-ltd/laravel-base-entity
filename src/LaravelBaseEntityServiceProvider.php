<?php

namespace Sysbox\LaravelBaseEntity;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;

class LaravelBaseEntityServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/' . LaravelBaseEntity::PACKAGE_NAME . '.php' => config_path(LaravelBaseEntity::PACKAGE_NAME . '.php'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/' . LaravelBaseEntity::PACKAGE_NAME . '.php', LaravelBaseEntity::PACKAGE_NAME . '.config');

        // Register the service the package provides.
        // this is for Facade
        $this->app->singleton(LaravelBaseEntity::PACKAGE_NAME, function ($app) {
            return new LaravelBaseEntity();
        });
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [LaravelBaseEntity::PACKAGE_NAME];
    }
}
