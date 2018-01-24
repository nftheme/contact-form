<?php

namespace Garung\ContactForm\Inputs;

use Garung\ContactForm\Abstracts\Input;
use NightFury\Form\Facades\Form;

class Submit extends Input
{
    /**
     * {@inheritDoc}
     */
    public $type = Input::SUBMIT;

    /**
     * {@inheritDoc}
     */
    public $value = '';

    /**
     * {@inheritDoc}
     * @var array
     */
    public $attributes = [];

    public function render()
    {
        $value = get_option($this->value, '');
        $html = Form::submit($value, $this->attributes);
        return $html;
    }

    public function renderMetaField()
    {
        $value = get_option($this->value, '');
        $html = Form::submit($value, $this->attributes);
        return $html;
    }
}
