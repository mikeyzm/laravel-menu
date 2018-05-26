<?php

namespace Lavary\Menu;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/menu.php', 'menu');

        $this->app->singleton(Menu::class, function ($app) {
            return new Menu();
        });
    }

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        // Extending Blade engine
        require_once 'blade/lm-attrs.php';

        $this->publishes([
            __DIR__.'/../../config/menu.php' => config_path('menu.php'),
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Menu::class];
    }
}
