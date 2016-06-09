<?php

namespace KodiCMS\Widgets\Providers;

use KodiCMS\Support\ServiceProvider;
use KodiCMS\Users\Model\Permission;
use KodiCMS\Widgets\Contracts\WidgetManager;
use KodiCMS\Widgets\Manager\WidgetManagerDatabase;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['view']->addNamespace('snippets', snippets_path());
    }

    public function register()
    {
        $this->app->singleton('widget.manager', function () {
            return new WidgetManagerDatabase();
        });

        $this->app->alias('widget.manager', WidgetManager::class);
        
        $this->registerProviders([
            BladeServiceProvider::class,
            EventsServiceProvider::class,
        ]);

        Permission::register('widgets', 'widget', [
            'list',
            'add',
            'delete',
            'edit',
        ]);

        Permission::register('widgets', 'widget_settings', [
            'cache',
            'roles',
            'location',
        ]);

        Permission::register('widgets', 'snippet', [
            'add',
            'edit',
            'list',
            'view',
            'delete'
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
                    'permissions' => 'snippet::list',
                    'priority' => 200,
                    'icon' => 'cutlery',
                ],
                [
                    'id' => 'widgets',
                    'title' => 'widgets::core.title.list',
                    'url' => route('backend.widget.list'),
                    'permissions' => 'widget::list',
                    'priority' => 300,
                    'icon' => 'cubes',
                ],
            ]);
        }
    }
}
