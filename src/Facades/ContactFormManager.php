<?php

namespace Vicoders\ContactForm\Facades;

use Illuminate\Support\Facades\Facade;
use Vicoders\ContactForm\Manager;

class ContactFormManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ContactFormManager';
    }
}
