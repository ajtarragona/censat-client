<?php

namespace Ajtarragona\Censat\Traits;
 
use Ajtarragona\Censat\Models\Eloquent\Select;
use Ajtarragona\Censat\Models\Eloquent\Map;

trait HasAttributes
{

    protected $attributes = [];
    protected $excluded = [];

    public function getAttributes(){  
        return $this->attributes; 
    }
    public function getExcluded(){  
        return $this->excluded;
    }

    public function setAttribute($attribute, $value){
        $this->{$attribute}=$value;
    }  

    public function getAttribute($attribute){  
        if($this->hasAttribute($attribute)) return $this->{$attribute} ?? null;
        return null;
    }

    public function hasAttribute($attribute){  
        if(!$this->attributes){
            if(!$this->excluded) return true;
            else return !in_array($attribute, $this->excluded);
        }else{
            if(in_array($attribute, $this->attributes)){
                return !in_array($attribute, $this->excluded);
            }else{
                return false;
            }
        }
    }

    public function attributeValues(){  
        return collect($this->attributes())->map(function($item){
            //TODO parsear las relacions, arrays, mapas, etc.
            // if($item instanceof Collection){

            // }else
            if($item instanceof Select){
                return $item->id;
            }else{
                return $item;
            }
        });
    }

    public function attributes(){  
        $vars=collect(get_object_vars($this));

        $ret=$vars->filter(function($value, $key){
            return $this->hasAttribute($key);
        })->toArray();
        
        return $ret;
    }

}