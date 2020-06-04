<?php

namespace eloquentFilter\Facade;

use Illuminate\Support\Facades\Facade as BaseFacade;

class EloquentFilter extends BaseFacade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'eloquentFilter';
    }
}
