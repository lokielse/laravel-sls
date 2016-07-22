<?php

namespace Lokielse\LaravelSLS\Facades;

use Illuminate\Support\Facades\Facade;

class WriterFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'sls.writer';
    }
}