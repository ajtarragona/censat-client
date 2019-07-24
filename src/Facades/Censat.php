<?php

namespace Ajtarragona\Censat\Facades; 

use Illuminate\Support\Facades\Facade;

class Censat extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'censat';
    }
}
