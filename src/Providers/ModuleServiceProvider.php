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

    public function contextBackend()
    {
        if ($navigation = \Navigation::getPages()->findById('design')) {
            $navigation->setFromArray([
                [
                    'id' => 'snippets',
                    'title' => 'widgets::snippet.title.list',
                    'url' => route('backend.snippet.list'),
                    'permissions' => 'snippet.index',
                    'priority' => 200,
                    'icon' => 'cutlery',
                ],
                [
                    'id' => 'widgets',
                    'title' => 'widgets::core.title.list',
                    'url' => route('backend.widget.list'),
                    'permissions' => 'widgets.index',
                    'priority' => 300,
                    'icon' => 'cubes',
                ],
            ]);
        }
    }
}
