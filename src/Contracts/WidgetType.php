<?php

namespace KodiCMS\Widgets\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface WidgetType extends Arrayable
{

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getClass();

    /**
     * @return string
     */
    public function getGroup();
}
