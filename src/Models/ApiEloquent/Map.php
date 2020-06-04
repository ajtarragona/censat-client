<?php

namespace Ajtarragona\Censat\Models\ApiEloquent;

use Ajtarragona\Censat\Traits\Castable;
use Ajtarragona\Censat\Traits\HasAttributes;

class Map {

    use Castable;
    use HasAttributes;
    
   /**
     * Class constructor.
     */
    public function __construct($name=null,$location=null)
    {
        $this->attributes=["name","location","infobox"];
        
    }
    
    public function url(){
        return route('censat.map.show',['name'=>$this->name, 'location'=>json_encode($this->location)]);
    }



    public function render(){
        return $this->url();
    }

}