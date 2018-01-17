<?php 
namespace Garung\ContactForm\Paginate;

use Garung\ContactForm\Manager;
use NF\Facades\Request;

class PaginationHelper
{
	public $menu_slug = Manager::MENU_SLUG;

	public function getNextPageUrl($name, $current_page, $total)
    {
		$next_page = $current_page + 1;
		if($next_page >= $total) {
			$next_page = $total;
		}
    	return get_admin_url() . 'admin.php?page=' . $this->menu_slug . '&tab=' . str_slug($name) . '&p=' . $next_page;
    }

    public function getPreviousPageUrl($name, $current_page)
    {
		$prev_page = $current_page - 1;
		if($prev_page < 1) {
			$prev_page = 1;
		}
    	return get_admin_url() . 'admin.php?page=' . $this->menu_slug . '&tab=' . str_slug($name) . '&p=' . $prev_page;
    }
}