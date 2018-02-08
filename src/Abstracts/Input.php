<?php

namespace Vicoders\ContactForm\Abstracts;

use Vicoders\ContactForm\Constracts\InputInterface;

abstract class Input implements InputInterface
{
    const TEXT     = 'text';
    const NUMBER   = 'number';
    const EMAIL    = 'email';
    const SELECT   = 'select';
    const IMAGE    = 'image';
    const GALLERY  = 'gallery';
    const TEXTAREA = 'textarea';
    const DATETIME = 'datetime';
    const DATE     = 'date';
    const SUBMIT   = 'submit';
    /**
     * Input type
     *
     * @var string
     */
    public $type;

    /**
     * Input label
     *
     * @var string
     */
    public $label = '';

    /**
     * Input name
     *
     * @var string
     */
    public $name = '';

    /**
     * Input value
     *
     * @var string
     */
    public $value = '';

    /**
     * Input description
     *
     * @var string
     */
    public $description = '';

    /**
     * Determine that value is required or not
     *
     * @var boolean
     */
    public $required = false;

    /**
     * Determine the type of input
     *
     * @var string
     * @return boolean
     */
    public function is($type)
    {
        return $this->type == $type;
    }

    public function save()
    {
        return update_option($this->name, $this->getValue());
    }

    public function remove()
    {
        return delete_option($this->name);
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
