<?php

namespace Lokielse\LaravelSLS\Facades;

use Illuminate\Support\Facades\Facade;

class LogFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'sls';
    }
}