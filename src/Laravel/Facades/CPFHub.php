<?php

namespace CPFHub\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class CPFHub extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cpfhub';
    }
}
