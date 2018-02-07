<?php

namespace Garung\ContactForm\Inputs;

use Garung\ContactForm\Abstracts\Input;
use NightFury\Form\Facades\Form;

class Date extends Input
{
    /**
     * {@inheritDoc}
     */
    public $type = Input::DATE;

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
    public $format = 'd-m-Y';

    /**
     * {@inheritDoc}
     * @var array
     */
    public $attributes = [];

    public function render()
    {
        $html = '';
        $html .= '<div class="wrap-group group-date-' . str_slug($this->name) . '">';
        if($this->label !== '') {
            $html .= Form::label($this->name, $this->label, ['class' => 'nfmodule-label-' . $this->name]);
        }
        $html .= Form::date($this->name, $this->format, $this->attributes);
        $html .= '</div>';
        return $html;
    }

    public function renderMetaField()
    {
        $html = '';
        $html .= '<div class="wrap-group group-date-' . str_slug($this->name) . '">';
        if($this->label !== '') {
            $html .= Form::label($this->name, $this->label, ['class' => 'nfmodule-label-' . $this->name]);
        }
        $html .= Form::date($this->name, $this->format, $this->attributes);
        $html .= '</div>';
        return $html;
    }
}
