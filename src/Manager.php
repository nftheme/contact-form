<?php

namespace Vicoders\ContactForm;

use Vicoders\ContactForm\Abstracts\Form;
use Vicoders\ContactForm\Abstracts\Input;
use Vicoders\ContactForm\Abstracts\Page;
use Vicoders\ContactForm\Inputs\Date;
use Vicoders\ContactForm\Inputs\DateTime;
use Vicoders\ContactForm\Inputs\Email;
use Vicoders\ContactForm\Inputs\Gallery;
use Vicoders\ContactForm\Inputs\Image;
use Vicoders\ContactForm\Inputs\Number;
use Vicoders\ContactForm\Inputs\Select;
use Vicoders\ContactForm\Inputs\Submit;
use Vicoders\ContactForm\Inputs\Text;
use Vicoders\ContactForm\Inputs\Textarea;
use Illuminate\Support\Collection;
use NF\Facades\Request;

class Manager
{
    const MENU_SLUG           = 'nf-theme-contact';
    const NTO_SAVED_SUCCESSED = 'nto_saved_successed';

    public $pages;
    public $forms;
    public $type;

    public function add($data)
    {
        $this->type = $data['type'];
        $page       = new Page();
        $form       = new Form();
        $page->setName($data['name']);
        $form->setName($data['name']);
        $form->setType($data['type']);
        $form->setStyle($data['style']);
        $form->setStatus($data['status']);
        if(isset($data['email_config'])) {
            $form->setConfigEmail($data['email_config']);
        }
        if(!empty($data['email_template'])) {
            $form->setTemplateEmail($data['email_template']);
        }
        if(!empty($data['email_variables'])) {
            $form->setVariableEmail($data['email_variables']);
        }
        if (!empty($data['status'])) {
            $flag        = false;
            $init_status = 0;
            foreach ($data['status'] as $key => $item) {
                if (!$flag) {
                    $init_status = $data['status'][0]['id'];
                }
                if ($item['is_default']) {
                    $init_status = $item['id'];
                    $flag        = true;
                }
            }
            $form->setInitStatus($init_status);
        }
        $fields = new Collection();
        foreach ($data['fields'] as $data) {
            $field = $this->prase($data);
            if ($field->is(Input::GALLERY) && isset($data['meta']) && is_array($data['meta'])) {
                $field->enable_meta = true;
                foreach ($data['meta'] as $meta_field_data) {
                    $meta_field = $this->prase($meta_field_data);
                    $field->addMetaField($meta_field);
                }
            }
            $fields->push($field);
        }

        $page->setFields($fields);
        if ($this->pages == null) {
            $this->pages = new Collection([$page]);
        } else {
            $this->pages->push($page);
        }

        $form->setFields($fields);
        if ($this->forms == null) {
            $this->forms = new Collection([$form]);
        } else {
            $this->forms->push($form);
        }
    }

    private function prase($field)
    {
        switch ($field['type']) {
            case Input::TEXT:
                $input              = new Text();
                $input->label       = isset($field['label']) ? $field['label'] : $input->label;
                $input->name        = isset($field['name']) ? $field['name'] : $input->name;
                $input->description = isset($field['description']) ? $field['description'] : $input->description;
                $input->required    = isset($field['required']) ? $field['required'] : $input->required;
                $input->attributes  = isset($field['attributes']) ? $field['attributes'] : $input->attributes;
                break;
            case Input::NUMBER:
                $input              = new Number();
                $input->label       = isset($field['label']) ? $field['label'] : $input->label;
                $input->name        = isset($field['name']) ? $field['name'] : $input->name;
                $input->description = isset($field['description']) ? $field['description'] : $input->description;
                $input->required    = isset($field['required']) ? $field['required'] : $input->required;
                $input->attributes  = isset($field['attributes']) ? $field['attributes'] : $input->attributes;
                break;
            case Input::TEXTAREA:
                $input              = new Textarea();
                $input->label       = isset($field['label']) ? $field['label'] : $input->label;
                $input->name        = isset($field['name']) ? $field['name'] : $input->name;
                $input->description = isset($field['description']) ? $field['description'] : $input->description;
                $input->required    = isset($field['required']) ? $field['required'] : $input->required;
                $input->attributes  = isset($field['attributes']) ? $field['attributes'] : $input->attributes;
                break;
            case Input::EMAIL:
                $input              = new Email();
                $input->label       = isset($field['label']) ? $field['label'] : $input->label;
                $input->name        = isset($field['name']) ? $field['name'] : $input->name;
                $input->description = isset($field['description']) ? $field['description'] : $input->description;
                $input->required    = isset($field['required']) ? $field['required'] : $input->required;
                $input->attributes  = isset($field['attributes']) ? $field['attributes'] : $input->attributes;
                break;
            case Input::SELECT:
                $input                    = new Select();
                $input->label             = isset($field['label']) ? $field['label'] : $input->label;
                $input->name              = isset($field['name']) ? $field['name'] : $input->name;
                $input->description       = isset($field['description']) ? $field['description'] : $input->description;
                $input->options           = isset($field['options']) ? $field['options'] : $input->options;
                $input->required          = isset($field['required']) ? $field['required'] : $input->required;
                $input->list              = isset($field['list']) ? $field['list'] : $input->list;
                $input->selected          = isset($field['selected']) ? $field['selected'] : $input->selected;
                $input->selectAttributes  = isset($field['selectAttributes']) ? $field['selectAttributes'] : $input->selectAttributes;
                $input->optionsAttributes = isset($field['optionsAttributes']) ? $field['optionsAttributes'] : $input->optionsAttributes;
                break;
            case Input::IMAGE:
                $input              = new Image();
                $input->label       = isset($field['label']) ? $field['label'] : $input->label;
                $input->name        = isset($field['name']) ? $field['name'] : $input->name;
                $input->description = isset($field['description']) ? $field['description'] : $input->description;
                $input->required    = isset($field['required']) ? $field['required'] : $input->required;
                $input->attributes  = isset($field['attributes']) ? $field['attributes'] : $input->attributes;
                break;
            case Input::GALLERY:
                $input              = new Gallery();
                $input->label       = isset($field['label']) ? $field['label'] : $input->label;
                $input->name        = isset($field['name']) ? $field['name'] : $input->name;
                $input->description = isset($field['description']) ? $field['description'] : $input->description;
                $input->required    = isset($field['required']) ? $field['required'] : $input->required;
                $input->attributes  = isset($field['attributes']) ? $field['attributes'] : $input->attributes;
                break;
            case Input::DATETIME:
                $input              = new DateTime();
                $input->label       = isset($field['label']) ? $field['label'] : $input->label;
                $input->name        = isset($field['name']) ? $field['name'] : $input->name;
                $input->description = isset($field['description']) ? $field['description'] : $input->description;
                $input->required    = isset($field['required']) ? $field['required'] : $input->required;
                $input->format      = isset($field['format']) ? $field['format'] : $input->format;
                $input->attributes  = isset($field['attributes']) ? $field['attributes'] : $input->attributes;
                break;
            case Input::DATE:
                $input              = new Date();
                $input->label       = isset($field['label']) ? $field['label'] : $input->label;
                $input->name        = isset($field['name']) ? $field['name'] : $input->name;
                $input->description = isset($field['description']) ? $field['description'] : $input->description;
                $input->required    = isset($field['required']) ? $field['required'] : $input->required;
                $input->format      = isset($field['format']) ? $field['format'] : $input->format;
                $input->attributes  = isset($field['attributes']) ? $field['attributes'] : $input->attributes;
                break;
            case Input::SUBMIT:
                $input             = new Submit();
                $input->value      = isset($field['value']) ? $field['value'] : $input->value;
                $input->attributes = isset($field['attributes']) ? $field['attributes'] : $input->attributes;
                break;
            default:
                throw new \Exception("Can not prase input field", 1);
                break;
        }
        return $input;
    }

    public function getTabUrl($name)
    {
        return get_admin_url() . 'admin.php?page=' . self::MENU_SLUG . '&tab=' . str_slug($name);
    }

    /**
     * Retrievie the list of pages
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Get page by slug
     *
     * @return array
     */

    public function getPage($slug)
    {
        if (!isset($slug)) {
            if ($this->getPages()->count() == 0) {
                throw new \Exception("Page not found", 1);
            }
            return $this->getPages()->first();
        }
        $k = $this->getPages()->search(function ($item) use ($slug) {
            return str_slug($item->name) == str_slug($slug);
        });
        if ($k === false) {
            throw new \Exception("Page not found", 1);
        }
        return $this->getPages()->get($k);
    }

    /**
     * Retrievie the list of form
     *
     * @return \Illuminate\Support\Collection
     */
    public function getForms()
    {
        return $this->forms;
    }

    /**
     * Get page by slug
     *
     * @return array
     */

    public function getForm($form_name)
    {
        if (!isset($form_name)) {
            if ($this->getForms()->count() == 0) {
                throw new \Exception("Form not found", 1);
            }
            return $this->getForms()->first();
        }
        $k = $this->getForms()->search(function ($item) use ($form_name) {
            return str_slug($item->name) == str_slug($form_name);
        });
        if ($k === false) {
            throw new \Exception("Form not found", 1);
        }
        return $this->getForms()->get($k);
    }

    /**
     * Determine page by slug
     *
     * @return boolean
     */

    public function isPage($name)
    {
        if (Request::has('tab')) {
            return Request::get('tab') === str_slug($name);
        } else {
            return str_slug($this->getPages()->first()->name) === str_slug($name);
        }
    }

    /**
     * Save data from request
     *
     * @return void
     */
    public function save()
    {
        $k = $this->getPages()->search(function ($item) {
            return $item->name == Request::get('page');
        });
        if ($k === false) {
            throw new \Exception("Request invalid", 1);
        }

        $page = $this->getPage(Request::get('page'));

        foreach ($page->fields as $field) {
            switch ($field->type) {
                case Input::GALLERY:
                    if (Request::get($field->name) != '') {
                        $items = json_decode(stripslashes(Request::get($field->name)), true);
                        $field->setItems(new Collection($items));
                        $field->save();
                    }
                    break;

                default:
                    $field->value = Request::get($field->name);
                    $field->save();
                    break;
            }
        }
        update_option(Manager::NTO_SAVED_SUCCESSED, 'should_flash', false);
        $redirect_url = $this->getTabUrl(Request::get('page'));
        wp_redirect($redirect_url);
    }

    /**
     * Remove value of an option
     *
     * @return void
     */
    public function remove()
    {
        $k = $this->getPages()->search(function ($item) {
            return str_slug($item->name) == str_slug(Request::get('page'));
        });
        if ($k === false) {
            throw new \Exception("Request invalid", 1);
        }

        $page = $this->getPage(Request::get('page'));

        foreach ($page->fields as $field) {
            if ($field->name == Request::get('field')) {
                $field->remove();
            }
        }
        $redirect_url = $this->getTabUrl(Request::get('page'));
        wp_send_json(['success' => true, 'redirect_url' => $redirect_url]);
    }

    public function getCurrentUrl()
    {
        $scheme = $_SERVER['SERVER_PORT'] == 80 ? 'http' : 'https';
        $url    = $scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        return $url;
    }

    public function modify_url($mod, $url = false)
    {
        // If $url wasn't passed in, use the current url
        if ($url == false) {
            $scheme = $_SERVER['SERVER_PORT'] == 80 ? 'http' : 'https';
            $url    = $scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

        // Parse the url into pieces
        $url_array = parse_url($url);

        // The original URL had a query string, modify it.
        if (!empty($url_array['query'])) {
            parse_str($url_array['query'], $query_array);
            foreach ($mod as $key => $value) {
                if (!empty($query_array[$key])) {
                    $query_array[$key] = $value;
                }
            }
        }

        // The original URL didn't have a query string, add it.
        else {
            $query_array = $mod;
        }

        return $url_array['scheme'] . '://' . $url_array['host'] . '/' . $url_array['path'] . '?' . http_build_query($query_array);
    }
}
