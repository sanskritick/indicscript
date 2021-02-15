<?php

namespace Sanskritick\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string transliterate(string $data, string $from, string $to, $options = null)
 * @method static bool isDevanagari($data)
 *
 * */

class IndicScript extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'indicscript';
    }
}
