<?php

namespace Ajtarragona\Censat\Traits;

use Cache;

trait Cachable
{
   
   protected $cache=true;
   protected $cache_seconds=360;
   
   public function setCache($cache){ 
      return $this->cache=$cache;
   }

   public function getCacheSeconds(){ 
      return $this->cache_seconds;
   }


  protected function getHash($options){
      return md5(json_encode(["census_name"=>$this->census_name,"entity_name"=>$this->entity_name,"options"=>$options]));
  }


  public function getCachedInstances($options=[]){
      if($this->cache){
         return Cache::remember( $this->getHash($options), $this->cache_seconds , function () use($options){
            return $this->getInstances($options);
         });
      }else{
         return $this->getInstances($options);
      }
   }

   

}