<?php

namespace Garung\ContactForm\Abstracts;

class Page
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
}
