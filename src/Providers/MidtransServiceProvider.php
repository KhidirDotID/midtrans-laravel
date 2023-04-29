<?php

namespace KhidirDotID\Midtrans\Providers;

use Illuminate\Support\ServiceProvider;
use KhidirDotID\Midtrans\Midtrans;

class MidtransServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'midtrans');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            $this->getConfigPath() => config_path('midtrans.php'),
        ], 'config');

        $this->app->singleton('midtrans', function ($app) {
            return new Midtrans;
        });

        Midtrans::registerMidtransConfig();
    }

    public function getConfigPath()
    {
        return __DIR__ . '/../../config/midtrans.php';
    }
}
