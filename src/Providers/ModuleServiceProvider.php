<?php

namespace KodiCMS\Widgets\Providers;

use KodiCMS\Support\ServiceProvider;
use KodiCMS\Widgets\Contracts\WidgetManager;
use KodiCMS\Widgets\Manager\WidgetManagerDatabase;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton('widget.manager', function () {
            return new WidgetManagerDatabase();
        });

        $this->app->alias('widget.manager', WidgetManager::class);

        $this->app['view']->addNamespace('snippets', snippets_path());
    }

    public function register()
    {
        $this->registerProviders([
            BladeServiceProvider::class,
            EventsServiceProvider::class,
        ]);
    }
}
