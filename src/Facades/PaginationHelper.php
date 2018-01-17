<?php

namespace Garung\ContactForm\Facades;

use Illuminate\Support\Facades\Facade;
use Garung\ContactForm\Manager;

class PaginationHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'PaginationHelper';
    }
}
