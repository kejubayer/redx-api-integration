<?php

namespace Kejubayer\RedxApiIntegration\Facades;

use Illuminate\Support\Facades\Facade;

class Redx extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'redx';
    }
}
