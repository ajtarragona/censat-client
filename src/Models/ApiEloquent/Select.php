<?php

namespace Ajtarragona\Censat\Models\ApiEloquent;

use Ajtarragona\Censat\Traits\Castable;
use Ajtarragona\Censat\Traits\HasAttributes;

class Select {

    use HasAttributes, 
        Castable;
    
    public function __construct()
    {
        $this->attributes=["id","value"];
    }
    
   

}