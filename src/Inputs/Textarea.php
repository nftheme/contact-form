<?php

namespace Garung\ContactForm\Inputs;

use Garung\ContactForm\Abstracts\Input;

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
        if ($this->required) {
            $html = <<<EOF
<div class="form-group {$this->name}">
    <label>{$this->label}</label>
    <textarea class="form-control" name="{$this->name}" required>{$value}</textarea>
</div>
EOF;
        } else {
            $html = <<<EOF
<div class="form-group {$this->name}">
    <label>{$this->label}</label>
    <textarea class="form-control" name="{$this->name}">{$value}</textarea>
</div>
EOF;
        }
        return $html;
    }

    public function renderMetaField()
    {
        $html = <<<EOF
<div class="form-group {$this->name}">
    <label>{$this->label}</label>
    <textarea class="form-control meta" name="{$this->name}"></textarea>
</div>
EOF;
        return $html;
    }
}
