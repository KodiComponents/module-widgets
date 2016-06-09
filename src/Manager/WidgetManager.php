<?php

namespace KodiCMS\Widgets\Manager;

use Illuminate\Support\Collection;
use KodiCMS\Widgets\Contracts\Widget;
use KodiCMS\Widgets\Contracts\WidgetCacheable;
use KodiCMS\Widgets\Contracts\WidgetCorrupt;
use KodiCMS\Widgets\Contracts\WidgetHandler;
use KodiCMS\Widgets\Contracts\WidgetManager as WidgetManagerInterface;
use KodiCMS\Widgets\Contracts\WidgetRenderable;
use KodiCMS\Widgets\WidgetType;

abstract class WidgetManager implements WidgetManagerInterface
{

    /**
     * @var Collection
     */
    protected $types;

    /**
     * WidgetManagerDashboard constructor.
     *
     * @param WidgetType[] $types
     */
    public function __construct(array $types = [])
    {
        $this->types = new Collection();

        foreach ($types as $type) {
            $this->registerWidget($type);
        }
    }

    /**
     * @param \KodiCMS\Widgets\Contracts\WidgetType $type
     *
     * @return $this
     */
    public function registerWidget(\KodiCMS\Widgets\Contracts\WidgetType $type)
    {
        if (! $this->isCorrupt($type->getClass())) {
            $this->types->put($type->getType(), $type);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAvailableTypes()
    {
        return $this->types;
    }
    
    /**
     * Проверка переданного класса на существоавние.
     *
     * @param string $class
     *
     * @return bool
     */
    public function isClassExists($class)
    {
        return class_exists($class);
    }

    /**
     * Проверка переданного класса на возможность быть виджетом.
     *
     * @param string $class
     *
     * @return bool
     */
    public function isWidgetable($class)
    {
        return ! $this->isCorrupt($class);
    }

    /**
     * Проверка переданного класса на существование и наличие интерфейса [\KodiCMS\Widgets\Contracts\Widget].
     *
     * @param string $class
     *
     * @return bool
     */
    public function isCorrupt($class)
    {
        if (! $this->isClassExists($class)) {
            return true;
        }

        $interfaces = class_implements($class);

        return in_array(WidgetCorrupt::class, $interfaces) or ! in_array(Widget::class, $interfaces);
    }

    /**
     * Проверка переданного класса на возможность быть Виджетом обработчиком.
     *
     * @param string $class
     *
     * @return bool
     */
    public function isHandler($class)
    {
        return $this->isClassExists($class) and in_array(WidgetHandler::class, class_implements($class));
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function isRenderable($class)
    {
        return $this->isClassExists($class) and in_array(WidgetRenderable::class, class_implements($class));
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function isCacheable($class)
    {
        return $this->isClassExists($class) and in_array(WidgetCacheable::class, class_implements($class));
    }

    /**
     * @param string $needleType
     *
     * @return array
     */
    public function getTemplateKeysByType($needleType)
    {
        $class = $this->getClassNameByType($needleType);

        if (is_null($class)) {
            return [];
        }

        $reflector = new \ReflectionClass($class);
        $comments = $reflector->getMethod('prepareData')->getDocComment();

        $keys = [];

        if (! empty($comments)) {
            $comments = str_replace(['/', '*', "\t", "\n", "\r"], '', $comments);
            preg_match_all("/\[(?s)(?m)(.*)\]/i", $comments, $found);

            if (! empty($found[1])) {
                $keys = explode(',', $found[1][0]);
            }
        }

        $keys[] = '[array] $settings';
        $keys[] = '[int] $widgetId';
        $keys[] = '[string] $header';
        $keys[] = '[array] $relatedWidgets';

        return $keys;
    }

    /**
     * @param string $needleType
     *
     * @return string|null
     */
    public function getClassNameByType($needleType)
    {
        $type = $this->types->filter(function (\KodiCMS\Widgets\Contracts\WidgetType $type) use ($needleType) {
            return $type->getType() == $needleType or $this->isCorrupt($type->getClass());
        })->first();

        if ($type) {
            return $type->getClass();
        }
    }

    /**
     * @param string $needleClass
     *
     * @return string|null
     */
    public function getTypeByClassName($needleClass)
    {
        $type = $this->types->filter(function (\KodiCMS\Widgets\Contracts\WidgetType $type) use ($needleClass) {
            return $type->getClass() == $needleClass or $this->isCorrupt($type->getClass());
        })->first();

        if ($type) {
            return $type->getType();
        }
    }

    /**
     * @param Collection $widgets
     *
     * @return Collection
     */
    public function buildWidgetCollection(Collection $widgets)
    {
        return $widgets->map(function ($widget) {
            return $widget->toWidget();
        })->filter(function ($widget) {
            return ! ($widget instanceof WidgetCorrupt);
        })->keyBy(function ($widget) {
            return $widget->getId();
        });
    }

    /**
     * @param string      $type
     * @param string      $name
     * @param string|null $description
     * @param array|null  $settings
     *
     * @return Widget|null
     */
    public function makeWidget($type, $name, $description = null, array $settings = null)
    {
        $class = $this->getClassNameByType($type);

        if (! $this->isWidgetable($class)) {
            return;
        }

        $widget = app($class, [$this, $name, $description]);

        if (! is_null($settings)) {
            $widget->setSettings($settings);
        }

        return $widget;
    }
}
