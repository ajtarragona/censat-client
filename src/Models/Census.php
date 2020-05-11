<?php

namespace Ajtarragona\Censat\Models; 

use Censat;
use Ajtarragona\Censat\Traits\SimpleCastable;

class Census{

    use SimpleCastable;

    public $id;
    public $short_name;
    public $name;
    public $description;
    
    
    public function __construct($args=null)
    {
        if($args && is_object($args)){
            if(isset($args->id)) $this->id = $args->id;
            if(isset($args->short_name)) $this->short_name = $args->short_name;
            if(isset($args->name)) $this->name = $args->name;
            if(isset($args->description)) $this->description = $args->description;
        }
            
    }

    public function entity($entity_name){
        $entity = Censat::entity($entity_name);
        $entity->forCensus($this->short_name);
        return $entity;

    }

    public function entities(){
       return Censat::censusEntities($this->short_name);
    }
}