<?php

namespace KodiCMS\Widgets\Http\Filters;

use KodiComponents\Support\Http\QueryFilters;

class WidgetFilters extends QueryFilters
{
    /**
     * @param string $type
     *
     * @return mixed
     */
    public function type($type = null)
    {
        if (! is_null($type)) {
            $this->builder->where('type', $type);
        }
    }
}