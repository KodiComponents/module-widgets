<?php

namespace KodiCMS\Widgets;

class WidgetType implements \KodiCMS\Widgets\Contracts\WidgetType
{

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $group;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $class;

    /**
     * WidgetType constructor.
     *
     * @param string $type
     * @param string $title
     * @param string $class
     * @param string $group
     */
    public function __construct($type, $title, $class, $group = 'Other')
    {
        $this->type = $type;
        $this->title = $title;
        $this->class = $class;
        $this->group = $group;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return strpos($this->title, '::') !== false ? trans($this->title) : $this->title;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'type' => $this->getType(),
            'title' => $this->getTitle(),
            'class' => $this->getClass(),
            'group' => $this->getGroup(),
        ];
    }
}