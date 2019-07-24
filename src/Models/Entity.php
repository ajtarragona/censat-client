<?php

namespace Ajtarragona\Censat\Models; 

use Censat;
use Ajtarragona\Censat\Traits\Castable;

class Entity{

    use Castable;
    
    public $id;
    public $short_name;
    public $name;
    public $description;
    
    private $census_name;
    
    
    public function __construct($args=null)
    {
        if($args && is_object($args)){
            if(isset($args->id)) $this->id = $args->id;
            if(isset($args->short_name)) $this->short_name = $args->short_name;
            if(isset($args->name)) $this->name = $args->name;
            if(isset($args->description)) $this->description = $args->description;
        }
        
    }
    
   

    public function forCensus($census_name){
        $this->census_name=$census_name;
    }

    public function fields($settings=false){
       return Censat::entityFields($this->short_name,$settings);
    }


    public function all($options=[]){
        if($this->census_name){
            return Censat::instances($this->census_name, $this->short_name, $options);
        }
    }


    public function get($id, $options=[]){
        if($this->census_name){
            return Censat::instance($this->census_name, $this->short_name, $id, $options);
        }
    }

    public function search($filters, $options=[]){
        if($this->census_name){
            $options["filters"] = json_encode($filters);

            return Censat::instances($this->census_name, $this->short_name, $options);
        }
    }
    

}