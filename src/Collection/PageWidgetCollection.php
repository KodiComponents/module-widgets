<?php

namespace KodiCMS\Widgets\Collection;

use Meta;
use KodiCMS\Widgets\Contracts\WidgetRenderable;
use KodiCMS\Widgets\Contracts\WidgetManager as WidgetManagerInterface;

class PageWidgetCollection extends WidgetCollection
{
    /**
     * @var WidgetManagerInterface
     */
    private $widgetManager;

    /**
     * @param WidgetManagerInterface $widgetManager
     * @param int                    $pageId
     */
    public function __construct(WidgetManagerInterface $widgetManager, $pageId)
    {
        $this->widgetManager = $widgetManager;

        $widgets = $widgetManager->getWidgetsByPage($pageId);
        $blocks = $widgetManager->getPageWidgetBlocks($pageId);

        foreach ($widgets as $widget) {
            $this->addWidget(
                $widget,
                array_get($blocks, $widget->getId().'.0'),
                array_get($blocks, $widget->getId().'.1')
            );
        }
    }

    /**
     * @return void
     */
    public function placeWidgetsToLayoutBlocks()
    {
        foreach ($this->registeredWidgets as $widget) {
            if (($object = $widget->getObject()) instanceof WidgetRenderable) {
                Meta::loadPackage($object->getMediaPackages());
            }
        }

        parent::placeWidgetsToLayoutBlocks();
    }

    /**
     * @return $this
     */
    protected function buildWidgetCrumbs()
    {
        foreach ($this->registeredWidgets as $id => $widget) {
            $widget = $widget['object'];
            if ($widget->hasBreadcrumbs()) {
                $widget->changeBreadcrumbs($this->getBreadcrumbs());
            }
        }

        return $this;
    }
}
