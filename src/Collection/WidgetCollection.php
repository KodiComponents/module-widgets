<?php

namespace KodiCMS\Widgets\Collection;

use Illuminate\Support\Collection;
use KodiCMS\Widgets\Contracts\Widget as WidgetInterface;
use KodiCMS\Widgets\Contracts\WidgetCollection as WidgetCollectionInterface;
use KodiCMS\Widgets\Contracts\WidgetCollectionItem;

class WidgetCollection implements WidgetCollectionInterface
{
    /**
     * @var Collection
     */
    protected $registeredWidgets;

    /**
     * @var Collection
     */
    protected $layoutBlocks;

    public function __construct()
    {
        $this->registeredWidgets = new Collection();
        $this->layoutBlocks = new Collection();
    }

    /**
     * @param int $id
     *
     * @return Widget|null
     */
    public function getWidgetById($id)
    {
        return $this->registeredWidgets->filter(function (WidgetCollectionItem $widget) use ($id) {
            return $widget->getObject()->getId() == $id;
        })->first();
    }

    /**
     * @return Collection
     */
    public function getRegisteredWidgets()
    {
        return $this->registeredWidgets;
    }

    /**
     * @param string $block
     *
     * @return Collection
     */
    public function getWidgetsByBlock($block)
    {
        return $this->registeredWidgets->filter(function (WidgetCollectionItem $widget) use ($block) {
            return $widget->getBlock() == $block;
        });
    }

    /**
     * @param WidgetInterface $widget
     * @param string          $block
     * @param int             $position
     *
     * @return $this
     */
    public function addWidget(WidgetInterface $widget, $block, $position = 500)
    {
        $this->registeredWidgets->push(new Widget($widget, $block, $position));

        return $this;
    }

    /**
     * @param integer $id
     *
     * @return void
     */
    public function removeWidget($id)
    {
        $this->registeredWidgets = $this->registeredWidgets->filter(function (WidgetCollectionItem $widget) use ($id) {
            return $widget->getBlock() != $id;
        });
    }

    /**
     * @return void
     */
    public function placeWidgetsToLayoutBlocks()
    {
        $this->sortWidgets();
        $this->registeredWidgets->each(function (WidgetCollectionItem $widget) {
            if (method_exists($widget->getObject(), 'onLoad')) {
                app()->call([$widget->getObject(), 'onLoad']);
            }
        })->each(function (WidgetCollectionItem $widget) {
            if (method_exists($widget->getObject(), 'afterLoad')) {
                app()->call([$widget->getObject(), 'afterLoad']);
            }
        });
    }

    /**
     * @return $this
     */
    protected function sortWidgets()
    {
        $this->registeredWidgets = $this->registeredWidgets->sortBy(function (WidgetCollectionItem $widget) {
            return $widget->getPosition();
        });

        return $this;
    }
}
