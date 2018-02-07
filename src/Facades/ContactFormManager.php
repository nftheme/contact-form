<?php

namespace Garung\ContactForm\Facades;

use Illuminate\Support\Facades\Facade;
use Garung\ContactForm\Manager;

class ContactFormManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ContactFormManager';
    }
}
