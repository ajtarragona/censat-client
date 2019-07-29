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
    
    protected $census_name;
    
    
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
        return $this;
    }

    public function field($short_name){
       return Censat::entityField($this->short_name,$short_name);
    }


    public function fields(){
       return Censat::entityFields($this->short_name);
    }


    
    public function relatedEntity($short_name){
       
        $field=$this->field($short_name);
        if($field) return $field->relatedEntity();
   
    }

    

    public function all($options=[]){
        if($this->census_name){
            if(isset($options["filters"])) unset($options["filters"]);
            return Censat::instances($this->census_name, $this->short_name, $options);
        }
    }


    public function get($id, $options=[]){
        if($this->census_name){
            $node=Censat::instance($this->census_name, $this->short_name, $id, $options);
            return new Instance($this->census_name, $this->short_name, $node);
        }
    }

    public function search($filters, $options=[]){
        if($this->census_name){
            return Censat::search($this->census_name, $this->short_name, $filters, $options);
        }
    }
    
    

    public function tree( $short_name, $options=[]){
        if($this->census_name){
            return Censat::instancesTree($this->census_name, $this->short_name, $short_name, $options);
        }
    }

    
    public function create( $options=[]){
        if($this->census_name){
            return Censat::createInstance($this->census_name, $this->short_name, $options);
        }
    }

    

}