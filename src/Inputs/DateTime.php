<?php

namespace Vicoders\ContactForm\Inputs;

use Vicoders\ContactForm\Abstracts\Input;
use NightFury\Form\Facades\Form;

class DateTime extends Input
{
    /**
     * {@inheritDoc}
     */
    public $type = Input::DATETIME;

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
        $value = get_option($this->name, '');
        $html = '';
        $html .= '<div class="wrap-group group-datetime-' . str_slug($this->name) . '">';
        if($this->label !== '') {
            $html .= Form::label($this->name, $this->label, ['class' => 'nfmodule-label-' . $this->name]);
        }
        $html .= Form::datetime($this->name, $this->format, $this->attributes);
        $html .= '</div>';
        return $html;
    }

    public function renderMetaField()
    {
        $value = get_option($this->name, '');
        $html = '';
        $html .= '<div class="wrap-group group-datetime-' . str_slug($this->name) . '">';
        if($this->label !== '') {
            $html .= Form::label($this->name, $this->label, ['class' => 'nfmodule-label-' . $this->name]);
        }
        $html .= Form::datetime($this->name, $this->format, $this->attributes);
        $html .= '</div>';
        return $html;
    }
}
