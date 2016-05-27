<?php

namespace KodiCMS\Widgets\Http\Forms;

use Illuminate\Http\Request;
use KodiCMS\Widgets\Model\Widget;
use KodiCMS\Widgets\Repository\WidgetRepository;
use KodiComponents\Support\Http\Form;

class CreateWidgetForm extends Form
{

    /**
     * @var WidgetRepository
     */
    protected $repository;

    /**
     * Form constructor.
     *
     * @param Request|null $request
     * @param WidgetRepository $repository
     */
    public function __construct(Request $request, WidgetRepository $repository)
    {
        parent::__construct($request);

        $this->repository = $repository;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'type' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function labels()
    {
        return trans('widgets::core.field');
    }

    /**
     * Persist the form.
     *
     * @return Widget
     */
    public function persist()
    {
        return $this->repository->create($this->request->all());
    }
}