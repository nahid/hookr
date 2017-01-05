<?php

namespace Nahid\Hookr\Facades;

use Illuminate\Support\Facades\Facade;

class Hook extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'hook';
    }
}
