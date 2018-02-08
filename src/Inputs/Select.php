<?php

namespace Vicoders\ContactForm\Inputs;

use Vicoders\ContactForm\Abstracts\Input;
use NightFury\Form\Facades\Form;

class Select extends Input
{
    /**
     * {@inheritDoc}
     */
    public $type = Input::SELECT;

    /**
     * {@inheritDoc}
     */
    public $label = '';

    /**
     * {@inheritDoc}
     */
    public $name = '';

    /**
     * {@inheritDoc}
     */
    public $description = '';

    /**
     * {@inheritDoc}
     */
    public $required = false;

    /**
     * {@inheritDoc}
     * @var array
     */
    public $list = [];

    /**
     * {@inheritDoc}
     * @var string
     */
    public $selected = null;

    /**
     * {@inheritDoc}
     * @var array
     */
    public $selectAttributes = [];

    /**
     * {@inheritDoc}
     * @var array
     */
    public $optionsAttributes = [];

    /**
     * list of available option
     *
     * @var array
     */
    public $options = [];

    public function render()
    {
        $html = '';
        $html .= '<div class="wrap-group group-select-' . str_slug($this->name) . '">';
        if($this->label !== '') {
            $html .= Form::label($this->name, $this->label, ['class' => 'nfmodule-label-' . $this->name]);
        }
        $html .= Form::select($this->name, $this->list, $this->selected, $this->selectAttributes, $this->optionsAttributes);
        $html .= '</div>';
        return $html;
    }

    private function renderSelection()
    {
        $html = '';
        $html .= '<div class="wrap-group group-select-' . str_slug($this->name) . '">';
        if($this->label !== '') {
            $html .= Form::label($this->name, $this->label, ['class' => 'nfmodule-label-' . $this->name]);
        }
        $html .= Form::select($this->name, $this->list, $this->selected, $this->selectAttributes, $this->optionsAttributes);
        $html .= '</div>';
        return $html;
    }

}
