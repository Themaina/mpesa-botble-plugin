<?php

namespace Botble\Mpesa\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Botble\Base\Facades\DashboardMenu;

class MpesaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Load plugin helpers
        $this->loadHelpers();
    }

    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/admin.php');

        // Load views and translations
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'plugins.mpesa');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'plugins/mpesa');

        // Publish config, views, etc.
        $this->publishes([
            __DIR__ . '/../../config/mpesa.php' => config_path('mpesa.php'),
        ], 'config');

        Event::listen(RouteMatched::class, function () {
            if (Route::has('mpesa.settings')) {
                DashboardMenu::registerItem([
                    'id'          => 'cms-plugins-mpesa',
                    'priority'    => 5,
                    'parent_id'   => null,
                    'name'        => 'M-Pesa Settings',
                    'icon'        => 'fas fa-money-check-alt',
                    'url'         => route('mpesa.settings'),
                    'permissions' => ['mpesa.settings'],
                ]);
            }
        });
    }

    protected function loadHelpers(): void
    {
        $helpers = glob(__DIR__ . '/../../helpers/*.php');
        foreach ($helpers as $helper) {
            require_once $helper;
        }
    }
}
