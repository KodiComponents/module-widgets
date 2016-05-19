<?php

namespace KodiCMS\Widgets\Contracts;

interface WidgetManager
{
    /**
     * @return array
     */
    public function getAvailableTypes();

    /**
     * @param string $needleType
     *
     * @return string|null
     */
    public function getClassNameByType($needleType);

    /**
     * @param string      $type
     * @param string      $name
     * @param string|null $description
     * @param array|null  $settings
     *
     * @return Widget|null
     */
    public function makeWidget($type, $name, $description = null, array $settings = null);

    /**
     * @param int $pageId
     *
     * @return \Illuminate\Support\Collection
     */
    public function getWidgetsByPage($pageId);

    /**
     * @param int $pageId
     *
     * @return array
     */
    public function getPageWidgetBlocks($pageId);
}
