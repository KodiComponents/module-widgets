<?php

namespace KodiCMS\Widgets\Http\Forms;

use KodiCMS\Widgets\Model\Widget;

class UpdateWidgetForm extends CreateWidgetForm
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
        ];
    }

    /**
     * Persist the form.
     *
     * @return Widget
     */
    public function persist()
    {
        return $this->repository->update(
            $this->request->route('id'),
            $this->request->all()
        );
    }
}