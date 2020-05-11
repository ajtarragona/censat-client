<?php

namespace Ajtarragona\Censat\Traits;
 

trait HasDates
{

    protected $default_dates = ['created_at','updated_at','deleted_at'];
    protected $dates = [];

}