<?php

namespace Opinodo\CintLibrary;

use Illuminate\Support\ServiceProvider;

class CintServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/cint.php';
        if (function_exists('config_path')) {
            $publishPath = config_path('cint.php');
        } else {
            $publishPath = base_path('config/cint.php');
        }
        $this->publishes([$configPath => $publishPath], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/cint.php';
        $this->mergeConfigFrom($configPath, 'cint');
    }
}