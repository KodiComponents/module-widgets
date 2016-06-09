<?php

namespace KodiCMS\Widgets\Contracts;

use KodiCMS\Widgets\Exceptions\WidgetException;

interface Model
{

    /**
     * @return string
     */
    public function getWidgetClass();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return \KodiCMS\Widgets\Contracts\Widget|null
     * @throws WidgetException
     */
    public function toWidget();

    /**
     * @return bool
     */
    public function isWidgetable();

    /**
     * @return bool
     */
    public function isHandler();

    /**
     * @return bool
     */
    public function isRenderable();

    /**
     * @return bool
     */
    public function isCacheable();

    /**
     * @return bool
     */
    public function isClassExists();

    /**
     * @return bool
     */
    public function isCorrupt();

    /**
     * @return array
     */
    public function getLocations();
}
