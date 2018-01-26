<?php

namespace Garung\ContactForm\Inputs;

use Garung\ContactForm\Abstracts\Input;
use NightFury\Form\Facades\Form;

class Textarea extends Input
{
    /**
     * {@inheritDoc}
     */
    public $type = Input::TEXTAREA;

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
    public $attributes = [];

    public function render()
    {
        $value = get_option($this->name, '');
        $html = '';
        $html .= '<div class="wrap-group group-textarea-' . str_slug($this->name) . '">';
        if($this->label !== '') {
            $html .= Form::label($this->name, $this->label, ['class' => 'nfmodule-label-' . $this->name]);
        }
        $html .= Form::textarea($this->name, $value, $this->attributes);
        $html .= '</div>';
        return $html;
    }

    public function renderMetaField()
    {
        $value = get_option($this->name, '');
        $html = '';
        $html .= '<div class="wrap-group group-textarea-' . str_slug($this->name) . '">';
        if($this->label !== '') {
            $html .= Form::label($this->name, $this->label, ['class' => 'nfmodule-label-' . $this->name]);
        }
        $html .= Form::textarea($this->name, $value, $this->attributes);
        $html .= '</div>';
        return $html;
    }
}
