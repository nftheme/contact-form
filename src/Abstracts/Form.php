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
     * [$email_module setting for email module]
     * @var array
     */
    public $email_enable = false;

    /**
     * [$email_module setting for email module]
     * @var array
     */
    public $email_config = [];

    /**
     * [$template_email description]
     * @var array
     */
    public $email_template = [];

    /**
     * [$email_variables description]
     * @var array
     */
    public $email_variables = [];

    /**
     * Redirect URL
     * @var string
     */
    public $redirect_url;

    /**
     * Message
     * @var string
     */
    public $message = 'Your message is sent';

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
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * [getType return type of form into config]
     * @return string [description]
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * [setType set a string is type of page]
     * @param string $type_page [description]
     */
    public function setStyle($style)
    {
        $this->style = $style;
        return $this;
    }

    /**
     * [getStyle return style of form into config]
     * @return string [description]
     */
    public function getStyle()
    {
        return $this->style;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * [setStatus description]
     * @param array $status [description]
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * [getStatus description]
     * @return array [description]
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * [setInitStatus description]
     * @param [type] $init_status [description]
     */
    public function setInitStatus($init_status)
    {
        $this->init_status = $init_status;
        return $this;
    }

    /**
     * [getInitStatus description]
     * @return integer [description]
     */
    public function getInitStatus()
    {
        return $this->init_status;
    }

    /**
     * [setEnable description]
     * @param boolean $email_enable [description]
     */
    public function setEnable($email_enable)
    {
        $this->email_enable = $email_enable;
        return $this;
    }

    /**
     * [getEnable description]
     * @return boolean [description]
     */
    public function getEnable()
    {
        return $this->email_enable;
    }

    /**
     * [setConfigEmail description]
     * @param boolean $config_email [description]
     */
    public function setConfigEmail($email_config)
    {
        $this->email_config = $email_config;
        return $this;
    }

    /**
     * [getConfigEmail description]
     * @return [type] [description]
     */
    public function getConfigEmail()
    {
        return $this->email_config;
    }

    /**
     * [setTemplateEmail description]
     * @param array $template_email [description]
     */
    public function setTemplateEmail($email_template)
    {
        $this->email_template = $email_template;
        return $this;
    }

    /**
     * [getTemplateEmail description]
     * @return array [description]
     */
    public function getTemplateEmail()
    {
        return $this->email_template;
    }

    /**
     * [setVariableEmail description]
     * @param array $email_variables [description]
     */
    public function setVariableEmail($email_variables)
    {
        $this->email_variables = $email_variables;
        return $this;
    }

    /**
     * [getVariableEmail description]
     * @return array [description]
     */
    public function getVariableEmail()
    {
        return $this->email_variables;
    }

    /**
     * Set Redirect URL
     * @param object $this
     */
    public function setRedirectUrl($url)
    {
        $this->redirect_url = $url;
        return $this;
    }

    /**
     * Get redirect url
     * @return string url
     */
    public function getRedirectUrl()
    {
        return $this->redirect_url;
    }

    /**
     * Set message
     * @param object $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get message
     * @return string message
     */
    public function getMessage()
    {
        return $this->message;
    }
}
