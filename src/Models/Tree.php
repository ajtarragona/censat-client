<?php

namespace Ajtarragona\Censat\Models; 

use Ajtarragona\Censat\Traits\SimpleCastable;

class Tree{

    use SimpleCastable;
    
    public $id;
    public $short_name;
    public $name;
    public $description;
    public $census_id;
    public $entity_id;
    public $census_name;
    public $entity_name;
    public $icon;
    public $color;
    
    
    public function __construct($args=null)
    {
        if($args && is_object($args)){
            foreach($args as $key=>$value){
                $this->$key=$value;
            }
        }
        
    }
   

}