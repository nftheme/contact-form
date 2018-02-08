<?php

namespace Vicoders\ContactForm\Abstracts;

class AdminPage
{
    /**
     * The text to be displayed in the title tags of the page when the menu is selected.
     *
     * @var string
     */
    public $page_title;

    /**
     * The text to be used for the menu.
     *
     * @var string
     */
    public $menu_title;

    /**
     * The capability required for this menu to be displayed to the user.
     *
     * @var string
     */
    public $capability = 'manage_options';

    /**
     * The slug name to refer to this menu by.
     *
     * @var string
     */
    public $menu_slug;

    /**
     * The URL to the icon to be used for this menu.
     *
     * @var string
     */
    public $icon_url;

    /**
     * The position in the menu order this one should appear.
     *
     * @var integer
     */
    public $position = 80;

    public function register()
    {
        add_action('admin_menu', function () {
            add_menu_page($this->page_title, $this->menu_title, $this->capability, $this->menu_slug, [$this, 'render'], $this->icon_url, $this->position);
        });
    }


    /**
     * @param string $page_title
     *
     * @return self
     */
    public function setPageTitle($page_title)
    {
        $this->page_title = $page_title;

        return $this;
    }

    /**
     * @param string $menu_title
     *
     * @return self
     */
    public function setMenuTitle($menu_title)
    {
        $this->menu_title = $menu_title;

        return $this;
    }

    /**
     * @param string $menu_slug
     *
     * @return self
     */
    public function setMenuSlug($menu_slug)
    {
        $this->menu_slug = $menu_slug;

        return $this;
    }
}
