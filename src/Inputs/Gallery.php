<?php

namespace Garung\ContactForm\Inputs;

use Illuminate\Support\Collection;
use Garung\ContactForm\Abstracts\Input;

class Gallery extends Input
{
    /**
     * {@inheritDoc}
     */
    public $type = Input::GALLERY;

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
    
    /**
     * List of items
     *
     * @var \Illuminate\Support\Collection
     */
    public $items;

    /**
     * Determine this input support metadata or not
     *
     * @var boolean
     */
    public $enable_meta = false;

    /**
     * List of meta field
     *
     * @var \Illuminate\Support\Collection
     */
    public $meta = false;

    /**
     * @param \Illuminate\Support\Collection $items
     *
     * @return self
     */
    public function setItems(\Illuminate\Support\Collection $items)
    {
        $this->items = $items;

        return $this;
    }

    public function render()
    {
        $value = get_option($this->name, get_template_directory_uri() . '/vendor/nf/option/assets/images/img-default.png');
        if ($value === false) {
            $this->items = new Collection();
        } else {
            $this->items = new Collection(json_decode($value, true));
        }
        $html = <<<EOF
<div class="nto-gallery-group">
    <div class="card nto-gallery" id="nto-image-{$this->name}">
        <textarea name="{$this->name}" class="form-control hidden input-value" style="font-size: 11px">{$value}</textarea>
        {$this->renderGallery()}
        <div class="card-body">
            <h4 class="card-title">{$this->label}</h4>
            <p class="card-text">{$this->description}</p>
            <a href="#" class="nto-gallery-upload-btn btn btn-primary" data-input="{$this->name}">Select File</a>
            <a href="#" class="nto-gallery-remove btn btn-secondary" data-input="{$this->name}">Remove all file</a>
        </div>
    </div>
EOF;

        if ($this->enable_meta) {
            $html .= <<<EOF
    <div class="modal fade meta-data-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
EOF;
            foreach ($this->meta as $meta_field) {
                $html .= $meta_field->renderMetaField();
            }
            $html .= <<<EOF
                <p class="description">* Options will not change until you save them</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary nto-save-meta">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

EOF;
        }
        return $html;
    }

    private function renderGallery()
    {
        $default_img = get_template_directory_uri() . '/vendor/nf/option/assets/images/3x4.png';
        if ($this->enable_meta) {
            $html = '<ul class="nto-items" data-img="' . $default_img . '" data-enable-meta="true">';
        } else {
            $html = '<ul class="nto-items" data-img="' . $default_img . '" data-enable-meta="false">';
        }
        foreach ($this->items as $item) {
            if ($this->enable_meta) {
                $html .= '<li class="nto-gallery-item"><img src="' . $default_img . '" style="background-image: url(\'' . $item['url'] . '\')" data-src="' . $item['url'] . '"><span class="meta-data"><span class="dashicons dashicons-admin-links"></span></span></li>';
            } else {
                $html .= '<li class="nto-gallery-item"><img src="' . $default_img . '" style="background-image: url(\'' . $item['url'] . '\')" data-src="' . $item['url'] . '"></li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }

    public function getValue()
    {
        return $this->items->toJson();
    }

    public function addMetaField($field)
    {
        if ($this->meta == null) {
            $this->meta = new Collection;
        }
        $this->meta->push($field);
        return $this;
    }
}
