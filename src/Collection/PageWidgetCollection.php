<?php

namespace KodiCMS\Widgets\Collection;

use KodiCMS\Widgets\Contracts\WidgetCollectionItem;
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
        parent::__construct();
        
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
        $this->registeredWidgets->each(function (WidgetCollectionItem $widget) {
            if (($object = $widget->getObject()) instanceof WidgetRenderable) {
                Meta::loadPackage($object->getMediaPackages());
            }
        });

        parent::placeWidgetsToLayoutBlocks();
    }
}
