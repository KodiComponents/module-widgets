<?php

namespace KodiCMS\Widgets\Widget;

use KodiCMS\Widgets\Contracts\WidgetCacheable;
use KodiCMS\Widgets\Traits\WidgetCache;

class HTML extends WidgetAbstract implements WidgetCacheable
{
    use WidgetCache;

    /**
     * @return array
     */
    public function prepareData()
    {
        return [

        ];
    }
}
