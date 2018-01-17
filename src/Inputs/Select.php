<?php

namespace Garung\ContactForm\Inputs;

use Garung\ContactForm\Abstracts\Input;
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
        if($this->label !== '') {
            $html .= Form::label($this->name, $this->label, ['class' => 'nfmodule-label-' . $this->name]);
        }
        if($this->required) {
            array_add($this->optionsAttributes, 'required', $this->required);
        }
        $html .= Form::select($this->name, $this->list, $this->selected, $this->selectAttributes, $this->optionsAttributes);
        return $html;
    }

    private function renderSelection()
    {
        $value = get_option($this->name);
        $html  = '';
        foreach ($this->options as $option) {
            if ($value === false) {
                if (isset($option['selected']) && $option['selected'] === true) {
                    $html .= "<option value={$option['value']} selected>{$option['label']}</option>";
                } else {
                    $html .= "<option value={$option['value']}>{$option['label']}</option>";
                }
            } else {
                if ($value == $option['value']) {
                    $html .= "<option value={$option['value']} selected>{$option['label']}</option>";
                } else {
                    $html .= "<option value={$option['value']}>{$option['label']}</option>";
                }
            }
        }
        return $html;
    }

}
