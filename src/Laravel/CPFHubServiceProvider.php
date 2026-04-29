<?php

namespace CPFHub\Laravel;

use CPFHub\CPFHub;
use Illuminate\Support\ServiceProvider;

class CPFHubServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CPFHub::class, function ($app) {
            return new CPFHub(config('services.cpfhub.key'));
        });

        $this->app->alias(CPFHub::class, 'cpfhub');
    }

    public function boot()
    {
        // Boot logic if needed
    }
}
