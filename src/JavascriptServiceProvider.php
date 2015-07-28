<?php

namespace Moon\Utilities\Javascript;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class JavascriptServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind('JavaScript', function ($app) {
            return new PHPToJavaScriptTransformer();
        });
    }

    /**
     * Register Facade.
     */
    public function boot()
    {
        AliasLoader::getInstance()->alias(
            'JavaScript',
            'Moon\Utilities\Javascript\JavaScriptFacade'
        );
    }
}
