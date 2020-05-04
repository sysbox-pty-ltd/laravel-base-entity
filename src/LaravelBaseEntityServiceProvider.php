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
            __DIR__.'/../config/laravelBaseEntity.php' => config_path('laravelBaseEntity.php'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravelBaseEntity.php', 'laravelBaseEntity.config');

        // Register the service the package provides.
        // this is for Facade
//        $this->app->singleton('laravelBaseEntity', function ($app) {
//            return new BaseEntity;
//        });
    }
}
