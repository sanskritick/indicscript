<?php

namespace Sanskritick\Script;

use Illuminate\Support\Facades\Facade;

class IndicScriptFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'indicscript';
    }
}
