<?php

namespace Sanskritick\Script;

use Illuminate\Support\ServiceProvider;

class IndicscriptServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->app->bind('indicscript', function () {
            return new Indicscript();
        });
    }
}
