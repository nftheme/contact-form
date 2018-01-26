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
        $html = Form::submit($this->value, $this->attributes);
        return $html;
    }

    public function renderMetaField()
    {
        $html = Form::submit($this->value, $this->attributes);
        return $html;
    }
}
