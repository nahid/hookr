<?php

namespace Nahid\Hookr;

use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;

class HookrServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
    }
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->registerHooker();
    }

    /**
     * Register Talk class.
     */
    protected function registerHooker()
    {
        $this->app->singleton('hook', function (Container $app) {
            return new Hook();
        });
        $this->app->alias('hook', Hook::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'hook',
        ];
    }
}
