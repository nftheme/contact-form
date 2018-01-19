<?php

namespace Garung\ContactForm\Pages;

use Garung\ContactForm\Abstracts\AdminPage;
use Garung\ContactForm\Facades\PaginationHelper;
use Garung\ContactForm\Manager;
use Garung\ContactForm\Models\Contact;
use League\Flysystem\Exception;
use NF\Facades\App;
use NF\Facades\Request;

class Option extends AdminPage
{
    public $page_title = 'Contact Manager';

    public $menu_title = 'Contact Manager';

    public $menu_slug = Manager::MENU_SLUG;

    public function render()
    {
        $name_tab = Request::get('tab');
        $manager  = App::make('ContactFormManager');
        $pages    = $manager->getPages();
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
        if(empty($name_tab)) {
            $name_tab = $current_page->name;
        }
        $page_query_param = Request::has('p') ? (int) Request::get('p') : 1;
        $per_page         = 2;
        $type_of_name     = (!empty($name_tab)) ? $name_tab : 'contact';
        $query            = new Contact();
        $query            = $query->where('type_of_name', $type_of_name);
        $total            = $query->count();
        $total_page       = $total/$per_page;

        $contact_data     = $query->skip(($page_query_param - 1) * $per_page)->take($per_page)->get();

        $next_page_url = PaginationHelper::getNextPageUrl($name_tab, $page_query_param, $total);
        $prev_page_url = PaginationHelper::getPreviousPageUrl($name_tab, $page_query_param);

        echo view('vendor.option.admin', compact('manager', 'pages', 'current_page', 'should_flash', 'contact_data', 'status_active', 'status_deactive', 'status_cancel', 'next_page_url', 'prev_page_url', 'total', 'page_query_param', 'total_page'));
    }
}
