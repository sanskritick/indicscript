<?php

namespace Sanskritick\Script;

use Illuminate\Support\ServiceProvider;

class IndicScriptServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->app->bind('indicscript', function () {
            return new IndicScript();
        });
    }
}
