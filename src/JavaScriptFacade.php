<?php

namespace Moon\Utilities\Javascript;

use Illuminate\Support\Facades\Facade;

class JavascriptFacade extends Facade
{
    /**
     * The name of the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'JavaScript';
    }
}
