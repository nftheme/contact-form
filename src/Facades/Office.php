<?php

namespace Vicoders\ContactForm\Facades;

use Illuminate\Support\Facades\Facade;

class Office extends Facade
{
    protected static function getFacadeAccessor()
    {
        return new \Vicoders\ContactForm\Services\Office;
    }
}
