<?php

namespace Ajtarragona\Censat\Traits;

use Ajtarragona\Censat\Models\Eloquent\Image;
use Cache;

trait WithImages
{
   
    public function image($field_name){
        //la imatge
         if($this->hasImage($field_name)){
             return Cache::remember('image-'.$field_name.'-'.$this->{$field_name}->id, $this->cache_seconds, function () {
                 $img=Image::cast($this->imatge);
                 $img->servei_id=$this->id;
                 return $img;
             });
 
         }
         return null;
     }
 
 
     public function hasImage($field_name){
         return isset($this->{$field_name}) && $this->{$field_name};
     }
 
     public function imageUrl($field_name){
         if($this->{$field_name}){
             return $this->image($field_name)->url();
         }
     }
 
 
     public function renderImage($field_name){
         if($this->{$field_name}){
             return $this->image($field_name)->render();
         }
     }
 
}