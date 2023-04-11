<?php

namespace Ajtarragona\Censat\Models;

use Ajtarragona\Censat\Traits\Castable;
use Ajtarragona\Censat\Traits\HasAttributes;

class GridInstance {
    
    protected $entity_name;
    protected $census_name;
    protected $grid_name;
    
    use HasAttributes, 
        Castable;
    

}