<?php

namespace Vicoders\ContactForm\Pages;

use Carbon\Carbon;
use League\Flysystem\Exception;
use NF\Facades\App;
use NF\Facades\Request;
use Vicoders\ContactForm\Abstracts\AdminPage;
use Vicoders\ContactForm\Facades\PaginationHelper;
use Vicoders\ContactForm\Manager;
use Vicoders\ContactForm\Models\Contact;

class Option extends AdminPage
{
    public $page_title = 'Form Manager';

    public $menu_title = 'Form Manager';

    public $menu_slug = Manager::MENU_SLUG;

    public function render()
    {
        $statusfilter = '';
        $param_page   = $this->menu_slug;
        $name_tab     = Request::get('tab');
        $manager      = App::make('ContactFormManager');

        $pages = $manager->getPages();
        if (!isset($pages)) {
            throw new \Exception("Please register your option scheme", 1);
        }
        $current_page = $manager->getPage(Request::get('tab'));
        $should_flash = false;
        if (get_option(Manager::NTO_SAVED_SUCCESSED) !== false) {
            $should_flash = true;
            delete_option(Manager::NTO_SAVED_SUCCESSED);
        }
        if (empty($name_tab)) {
            $name_tab = str_slug($current_page->name);
        }
        $page_query_param = Request::has('p') ? (int) Request::get('p') : 1;
        $per_page         = 25;
        $type_of_name     = $name_tab;
        $query            = new Contact();
        $query            = $query->where('name_slug', $name_tab);
        $total            = $query->count();
        $total_page       = round($total / $per_page);
        if ($total_page <= 0) {
            $total_page = 1;
        }
        if (Request::has('statusfilter')) {
            $statusfilter = (int) Request::get('statusfilter');
            if ($statusfilter !== -1) {
                $query = $query->where('status', $statusfilter);
            }
        }
        $query        = $query->orderBy('id', 'DESC');
        $contact_data = $query->skip(($page_query_param - 1) * $per_page)->take($per_page)->get();
        $contact_data = $contact_data->map(function ($item) {
            $item->created = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->format('l, d-m-Y');
            $item->updated = Carbon::createFromFormat('Y-m-d H:i:s', $item->updated_at)->format('l, d-m-Y');
            return $item;
        });
        $form           = $manager->getForm($name_tab);
        $list_status    = $form->getStatus();
        $template_email = $form->getTemplateEmail();
        $config_email   = $form->getConfigEmail();

        $next_page_url = PaginationHelper::getNextPageUrl($name_tab, $page_query_param, $total);
        $prev_page_url = PaginationHelper::getPreviousPageUrl($name_tab, $page_query_param);

        echo \Vicoders\ContactForm\Facades\View::render('contact_admin', compact('manager', 'pages', 'current_page', 'should_flash', 'contact_data', 'next_page_url', 'prev_page_url', 'total', 'page_query_param', 'total_page', 'list_status', 'param_page', 'name_tab', 'statusfilter', 'template_email', 'config_email'));
    }
}
