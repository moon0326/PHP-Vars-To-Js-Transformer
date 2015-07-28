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
    }

    /**
     * Register Facade.
     */
    public function boot()
    {
        AliasLoader::getInstance()->alias(
            'JavaScript',
            'Moon\PHPToJavaScriptTransformer\JavaScriptFacade'
        );
    }
}
