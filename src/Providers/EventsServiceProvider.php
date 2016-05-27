<?php

namespace KodiCMS\Widgets\Providers;

use PackageManager;
use KodiCMS\Pages\Helpers\Block;
use KodiCMS\Users\Model\Role;
use KodiCMS\Widgets\Model\Widget;
use KodiCMS\Widgets\Model\SnippetCollection;
use KodiCMS\Widgets\Contracts\WidgetPaginator;
use KodiCMS\Widgets\Collection\PageWidgetCollection;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use KodiCMS\Widgets\Observers\WidgetObserver;
use Request;
use KodiCMS\Pages\Model\Page;

class EventsServiceProvider extends BaseEventServiceProvider
{
    /**
     * Register any other events for your application.
     *
     * @param  DispatcherContract $events
     *
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        $events->listen('KodiCMS\Pages\Events\FrontPageFound', function ($event) {
            $this->app->singleton('layout.widgets', function ($app) use ($event) {
                return new PageWidgetCollection(
                    $app['widget.manager'],
                    $event->getPage()->getId()
                );
            });

            $block = new Block(app('layout.widgets'));
            $this->app->singleton('layout.block', function ($app) use ($block) {
                return $block;
            });
        }, 9000);

        $events->listen('view.page.create', function ($page) {
            echo view('widgets::widgets.page.create')->with('page', $page)->with('pages', $page->getSitemap())->render();
        });

        $events->listen('view.page.edit', function ($page) {
            if (acl_check('widgets::list') and $page->hasLayout()) {
                echo view('widgets::widgets.page.iframe', compact('page'))->render();
            }
        });

        $events->listen('view.widget.edit', function ($widget) {
            if ($widget->isRenderable()) {
                $commentKeys = app('widget.manager')->getTemplateKeysByType($widget->type);
                $snippets = (new SnippetCollection())->getHTMLSelectChoices();

                echo view('widgets::widgets.partials.renderable', compact('widget', 'commentKeys', 'snippets'))->render();
            }

            if ($widget->isCacheable() and acl_check('widget_settings::cache')) {
                echo view('widgets::widgets.partials.cacheable', compact('widget'))->render();
            }
        });

        $events->listen('view.widget.edit.footer', function ($widget) {
            if ($widget->isRenderable()) {
                $assetsPackages = PackageManager::getHTMLSelectChoice();
                $widgetList = Widget::where('id', '!=', $widget->id)->lists('name', 'id')->all();

                echo view('widgets::widgets.partials.renderable_buttons', compact('widget', 'commentKeys', 'snippets', 'assetsPackages', 'widgetList'))->render();
            }

            if (acl_check('widget_settings::roles') and ! $widget->isHandler()) {
                $usersRoles = Role::pluck('name', 'id')->all();
                echo view('widgets::widgets.partials.permissions', compact('widget', 'usersRoles'))->render();
            }
        });

        $events->listen('view.widget.edit.settings', function ($widget) {
            if ($widget->toWidget() instanceof WidgetPaginator) {
                echo view('widgets::widgets.paginator.widget', [
                    'widget' => $widget->toWidget(),
                ])->render();
            }
        });

        $events->listen('view.widget.edit.footer', function ($widget) {
            if ($widget->isHandler()) {
                echo view('widgets::widgets.partials.handler', compact('widget'))->render();
            }
        });

        Page::created(function ($page) {
            $pageId = array_get(Request::get('widgets'), 'from_page_id');

            if (! empty($pageId)) {
                app('widget.manager')->copyWidgets($pageId, $page->id);
            }
        });

        Page::deleted(function ($page) {
            app('widget.manager')->deleteWidgetsFromPage($page->id);
        });

        Page::saving(function ($page) {
            $postData = Request::input('widget', []);

            foreach ($postData as $widgetId => $location) {
                if (array_key_exists('block', $location)) {
                    app('widget.manager')->updateWidgetOnPage($widgetId, $page->id, $location);
                }
            }
        });

        Widget::observe(new WidgetObserver);
    }
}
