<?php

namespace Garung\ContactForm\Inputs;

use Garung\ContactForm\Abstracts\Input;
use NightFury\Form\Facades\Form;

class Text extends Input
{
    /**
     * {@inheritDoc}
     */
    public $type = Input::TEXT;

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
        if($this->label !== '') {
            $html .= Form::label($this->name, $this->label, ['class' => 'nfmodule-label-' . $this->name]);
        }
        if($this->required) {
            array_add($this->attributes, 'required', $this->required);
        }
        $html .= Form::text($this->name, $value, $this->attributes);
        return $html;
    }

    public function renderMetaField()
    {
        $html = <<<EOF
<div class="form-group {$this->name}">
    <label>{$this->label}</label>
    <input type="text" class="form-control meta" name="{$this->name}">
</div>
EOF;
        return $html;
    }
}
