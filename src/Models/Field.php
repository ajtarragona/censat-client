<?php

namespace Ajtarragona\Censat\Models; 

use Censat;
use Ajtarragona\Censat\Traits\SimpleCastable;

class Field{

    use SimpleCastable;

    public $entity_name;
    public $short_name;
    public $label;
    public $type;
    private $settings;
    
    
    public function __construct($entity_name,$short_name,$args=null)
    {
        if($args && is_object($args)){
            
            $this->entity_name = $entity_name;
            $this->short_name = $short_name;
            $this->settings=$args;
            if(isset($args->label)) $this->label = $args->label;
            if(isset($args->type)) $this->type = $args->type->short_name;
            
        }
            
    }

    public function settings(){
        return $this->settings;
    }
    


    public function options(){
        if($this->type=="select"){
            if(isset($this->settings->options))
                return $this->settings->options;

            return null;
        }
    }
    

    public function gridFields(){
        if($this->type=="grid"){
            return Censat::entityGridFields($this->entity_name,$this->short_name);
        }
        return null;
    }



    public function relatedEntity(){
       
            if($this->type=="relation"){
                $relatedentity=$this->settings->entity;
                
                if($relatedentity && is_array($relatedentity) && count($relatedentity)==2 && $relatedentity[0] && $relatedentity[1]){
                    $relentity= Censat::entity($relatedentity[1]);
                    $relentity->forCensus($relatedentity[0]);
                    return $relentity;
                }
            }
       
    }

    
    
  

}