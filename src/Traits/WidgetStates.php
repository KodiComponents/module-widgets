<?php

namespace KodiCMS\Widgets\Traits;


trait WidgetStates
{
    /**
     * @return bool
     */
    public function isWidgetable()
    {
        return ($this->exists and $this->manager->isWidgetable($this->getWidgetClass()));
    }

    /**
     * @return bool
     */
    public function isHandler()
    {
        return $this->manager->isHandler($this->getWidgetClass());
    }

    /**
     * @return bool
     */
    public function isRenderable()
    {
        return $this->manager->isRenderable($this->getWidgetClass());
    }

    /**
     * @return bool
     */
    public function isCacheable()
    {
        return $this->manager->isCacheable($this->getWidgetClass());
    }

    /**
     * @return bool
     */
    public function isClassExists()
    {
        return $this->manager->isClassExists($this->getWidgetClass());
    }

    /**
     * @return bool
     */
    public function isCorrupt()
    {
        return $this->manager->isCorrupt($this->getWidgetClass());
    }
}