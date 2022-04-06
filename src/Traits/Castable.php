<?php

namespace Ajtarragona\Censat\Traits;

use Ajtarragona\Censat\Models\ApiEloquent\Image;
use Ajtarragona\Censat\Models\ApiEloquent\Map;
use Ajtarragona\Censat\Models\ApiEloquent\Select;
use Ajtarragona\Censat\Models\Instance;

use Carbon\Carbon;
use Illuminate\Support\Collection;

trait Castable
{
    
    public function __toString(){
        return json_encode($this);
    }
    
    public static function castAll(Collection $items=null){
       if(!$items) return null;
       return $items->map(function($item){
            return self::cast($item);
       });
    }

    protected function parseValue($value, $attr){
        $val=null;
        if($value){


            if(isset($this->dates) && in_array($attr, array_merge($this->default_dates, $this->dates ?? []) ) ){
                //es una fecha
                $val=Carbon::parse($value);
            }else if(isset($this->images) && in_array($attr, $this->images)){
                //si es una imagen
                $val=Image::cast($value);
                
            }elseif(isset($this->maps) && in_array($attr, $this->maps)){
                //si es un mapa
                // dd($value);
                $val=Map::cast($value[0]);
            }elseif(isset($this->multi_maps) && in_array($attr, $this->multi_maps)){
                //si es un mapa multiple
                $val=Map::castAll(collect($value));
            }elseif(isset($this->selects) && in_array($attr, $this->selects)){
                //si es un select
                if(is_array($value)){
                    $val=collect();
                    foreach($value as $v){
                        $tmp=Select::cast($v);
                        $val->push($tmp);
                    }
                }else{
                    $val=Select::cast($value);
                }
                
            }elseif(isset($this->relations) && in_array($attr, array_keys($this->relations))){
                //si es una relacion
                $classname= $this->relations[$attr];
                // dd($classname);
                if(is_array($value)){
                    $val=collect();
                    foreach($value as $v){
                        $tmp=new $classname;
                        $tmp->set($v);
                        $val->push($tmp);
                    }
                }else{
                    $val=new $classname;
                    $val->set($value);
                }
                
            }else{
                $val = $value ?? null;
            }
        }
        return $val;
    }


    public function set($args, $value=null){

        if($args instanceof Instance){
            $ret= self::fromInstance($args);
            // $ret->setInstance($args);
            return $ret;
        }else{
            if(is_string($args) && $this->hasAttribute($args)){
                $this->{$args} = $value;
            }else{
                if(is_object($args)) $args = to_array_first($args);
                // dump($args);
                if(is_array($args)){
                    foreach($args as $key=>$value){
                        if($this->hasAttribute($key)){
                            $this->setAttribute($key ,$this->parseValue($value,$key));
                        }
                    }
                } 
            }
            return $this;
        }
    }



    public static function cast($args){
        return (new static)->set($args);
    }

    protected static function fromInstance(Instance $instance){
        $tmp=new static;
        // dd(array_keys(get_object_vars($instance)));
        foreach(array_keys(get_object_vars($instance)) as $attr){
            if($tmp->hasAttribute($attr)){
                $val=$tmp->parseValue($instance->{$attr}, $attr);
                $tmp->setAttribute($attr, $val);//$instance->{$var} ?? null;
            }
        }
        return $tmp;
    }

}