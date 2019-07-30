<?php

namespace Ajtarragona\Censat\Models; 

use Censat;
use Ajtarragona\Censat\Traits\Castable;

class Instance{

    use Castable;

    public $entity_name;
    public $census_name;
    // public $id;
    // public $instance_label;
    
    
    
    
    public function __construct($census_name,$entity_name,$args=null)
    {
        if($args && is_object($args)){
            
            $this->census_name = $census_name;
            $this->entity_name = $entity_name;
            // $this->settings=$args;
            // if(isset($args->id)) $this->id = $args->id;
            foreach($args as $key=>$value){
                $this->$key=$value;
            }
            // if(isset($args->name)) $this->instance_label = $args->name;
        }
            
    }


    public function entity(){
        if($this->entity_name) return Censat::entity(($this->entity_name);
        return null;
    }

    
    public function census(){
        if($this->census_name) return Censat::census(($this->census_name);
        return null;
    }


    public function update($fields){
        if($this->census_name && $this->entity_name && isset($this->id)){
            return Censat::updateInstance($this->census_name, $this->entity_name, $this->id, $fields);
        }
    }

    public function get($field_name){
        if($this->census_name && $this->entity_name && isset($this->id)){
            return Censat::getInstanceField($this->census_name, $this->entity_name, $this->id, $field_name);
        }
    }

    public function set($field_name, $value){
        if($this->census_name && $this->entity_name && isset($this->id)){
            return Censat::updateInstanceField($this->census_name, $this->entity_name, $this->id, $field_name,$value);
        }
    }

    public function add($field_name, $value){
        if($this->census_name && $this->entity_name && isset($this->id)){
            return Censat::addInstanceFieldItem($this->census_name, $this->entity_name, $this->id, $field_name, $value);
        }
    }

    public function clear($field_name){
        if($this->census_name && $this->entity_name && isset($this->id)){
            return Censat::clearInstanceField($this->census_name, $this->entity_name, $this->id, $field_name);
        }
    }
    
    public function remove($field_name, $item_id){
        if($this->census_name && $this->entity_name && isset($this->id)){
            return Censat::removeInstanceFieldItem($this->census_name, $this->entity_name, $this->id, $field_name, $item_id);
        }
    }


    public function delete(){
        if($this->census_name && $this->entity_name && isset($this->id)){
            return Censat::deleteInstance($this->census_name, $this->entity_name, $this->id);
        }
    }


    public function destroy(){
        if($this->census_name && $this->entity_name && isset($this->id)){
            return Censat::deleteInstance($this->census_name, $this->entity_name, $this->id, true);
        }
    }


   

}