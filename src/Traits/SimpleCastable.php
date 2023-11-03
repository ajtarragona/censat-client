<?php

namespace Ajtarragona\Censat\Traits;


trait SimpleCastable
{
    public static function cast($args){
    
        if(is_array($args) || is_collection($args) ){
            $ret=collect();
            foreach($args as $arg){
                $ret->push(new self($arg));
            }
            return $ret;
        }else{
            return new self($args);
        }
    }
}