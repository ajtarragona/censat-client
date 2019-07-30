<?php

namespace Ajtarragona\Censat\Traits;


trait Castable
{
    public static function cast($args){
    
        if(is_array($args)){
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