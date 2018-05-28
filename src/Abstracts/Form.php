<?php

namespace Vicoders\ContactForm\Abstracts;

class Form
{
    /**
     * Page name
     *
     * @var string
     */
    public $name = '';

    /**
     * All fields of this page
     *
     * @var Illuminate\Support\Collection
     */
    public $fields;

    /**
     * [$type type of form: contact or subcribe]
     * @var string
     */
    public $type;

    /**
     * [$style style for form]
     * @var [type]
     */
    public $style = 'form-1';

    /**
     * [$status status for records in admin page]
     * @var array
     */
    public $status = [];

    /**
     * [$init_status init value of status ]
     * @var integer
     */
    public $init_status = 0;

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param Illuminate\Support\Collection $fields
     *
     * @return self
     */
    public function setFields(\Illuminate\Support\Collection $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @return self
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * [setType set a string is type of page]
     * @param string $type_page [description]
     */
    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    /**
     * [getType return type of form into config]
     * @return string [description]
     */
    public function getType() {
        return $this->type;
    }

    /**
     * [setType set a string is type of page]
     * @param string $type_page [description]
     */
    public function setStyle($style) {
        $this->style = $style;
        return $this;
    }

    /**
     * [getStyle return style of form into config]
     * @return string [description]
     */
    public function getStyle() {
        return $this->style;
    }

    public function getName() {
        return $this->name;
    }

    /**
     * [setStatus description]
     * @param array $status [description]
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    /**
     * [getStatus description]
     * @return array [description]
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * [setInitStatus description]
     * @param [type] $init_status [description]
     */
    public function setInitStatus($init_status) {
        $this->init_status = $init_status;
        return $this;
    }

    /**
     * [getInitStatus description]
     * @return integer [description]
     */
    public function getInitStatus() {
        return $this->init_status;
    }
}
