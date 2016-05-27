<?php

namespace KodiCMS\Widgets\Repository;

use DB;
use Illuminate\Http\Request;
use KodiCMS\Widgets\Model\Widget;
use KodiCMS\CMS\Repository\BaseRepository;

class WidgetRepository extends BaseRepository
{
    /**
     * @param Widget $model
     */
    public function __construct(Widget $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int   $id
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update($id, array $data = [])
    {
        return parent::update($id, array_except($data, ['type']));
    }

    /**
     * @param $pageId
     *
     * @return array
     */
    public function getByPageId($pageId)
    {
        intval($pageId);

        $query = DB::table('page_widgets')->select('widget_id');

        if ($pageId > 0) {
            $query->where('page_id', $pageId);
        }

        $ids = $query->pluck('widget_id');

        $widgetList = $this->model->newQuery();

        if (count($ids) > 0) {
            $widgetList->whereNotIn('id', $ids);
        }

        return $widgetList->get()->filter(function($widget) {
            return !$widget->isCorrupt() and !$widget->isHandler();
        })->groupBy(function ($widget) {
            return $widget->getType();
        });
    }
}
