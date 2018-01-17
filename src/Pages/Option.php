<?php

namespace Garung\ContactForm\Pages;

use Garung\ContactForm\Abstracts\AdminPage;
use Garung\ContactForm\Manager;
use Garung\ContactForm\Models\Contact;
use NF\Facades\App;
use NF\Facades\Request;

class Option extends AdminPage
{
    public $page_title = 'Contact Manager';

    public $menu_title = 'Contact Manager';

    public $menu_slug = Manager::MENU_SLUG;

    public function render()
    {
        $manager = App::make('ContactFormManager');
        $pages   = $manager->getPages();
        if (!isset($pages)) {
            throw new \Exception("Please register your option scheme", 1);
        }
        $current_page    = $manager->getPage(Request::get('tab'));
        $should_flash    = false;
        $status_active   = Contact::ACTIVE;
        $status_deactive = Contact::DEACTIVE;
        $status_cancel   = Contact::CANCEL;
        if (get_option(Manager::NTO_SAVED_SUCCESSED) !== false) {
            $should_flash = true;
            delete_option(Manager::NTO_SAVED_SUCCESSED);
        }
        $contact_data = Contact::where('type_of_name', Request::get('tab'))->paginate(1);
        echo view('vendor.option.admin', compact('manager', 'pages', 'current_page', 'should_flash', 'contact_data', 'status_active', 'status_deactive', 'status_cancel'));
    }
}
