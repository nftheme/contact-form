<?php

namespace Vicoders\ContactForm\Facades;

use Illuminate\Support\Facades\Facade;

class View extends Facade
{
    protected static function getFacadeAccessor()
    {
        return new \Vicoders\ContactForm\Services\View;
    }
}
