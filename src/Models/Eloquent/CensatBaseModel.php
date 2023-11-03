<?php

namespace Ajtarragona\Censat\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CensatBaseModel extends Model
{
    
    protected $connection = 'censat';
    
    protected $simple_relations = [];
    protected $multiple_relations = [];
    protected $simple_inverse_relations = [];
    protected $multiple_inverse_relations = [];
    protected $grids = [];
    protected $simple_selects = [];
    protected $multiple_selects = [];

    /** TODO maps, integraciones. De momento añadirlo a $casts */ 
    protected $maps = [];
    protected $integrations = [];
    

     /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if(
            ($this->simple_selects && array_key_exists(Str::snake($key), $this->simple_selects))
            || ($this->simple_relations && array_key_exists(Str::snake($key), $this->simple_relations))
            || ($this->simple_inverse_relations && array_key_exists(Str::snake($key), $this->simple_inverse_relations))
        ){
            return $this->__call($key,null)->first();
        }
        
        if( 
            ($this->multiple_selects && array_key_exists(Str::snake($key), $this->multiple_selects) ) 
            || ($this->multiple_relations && array_key_exists(Str::snake($key), $this->multiple_relations) ) 
            || ($this->multiple_inverse_relations && array_key_exists(Str::snake($key), $this->multiple_inverse_relations) ) 
            || ($this->grids && array_key_exists(Str::snake($key), $this->grids))
        ){
            return $this->__call($key,null)->get();
        }
    
        
        return parent::__get($key);
    }
    

    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if($this->simple_selects && array_key_exists(Str::snake($method), $this->simple_selects)){
            $class_name  = $this->simple_selects[Str::snake($method)];
            return $this->belongsTo($class_name, Str::snake($method).'_id');
        }
        

        if($this->multiple_selects && array_key_exists(Str::snake($method), $this->multiple_selects)){
            $select_class=$this->multiple_selects[Str::snake($method)];
            $select_model= new $select_class;
            return $this->belongsToMany($select_class, $select_model->getCrossTable() , $this->entity_name."_id", $select_model->field_name."_id");
        }


        if($this->simple_relations && array_key_exists(Str::snake($method), $this->simple_relations)){
            // dump($this->simple_relations, $method);
            $class_name  = $this->simple_relations[Str::snake($method)];
            return $this->belongsTo($class_name, Str::snake($method).'_id');
            
            
        }

        //TODO
        // if($this->simple_inverse_relations && array_key_exists(Str::snake($method), $this->simple_inverse_relations)){
        //     // dump($this->simple_relations, $method);
        //     $class_name  = $this->simple_inverse_relations[Str::snake($method)];
        //     return $this->belongsTo($class_name, Str::snake($method).'_id');
            
            
        // }

        if($this->multiple_relations && array_key_exists(Str::snake($method), $this->multiple_relations)){
            $relation_class=$this->multiple_relations[Str::snake($method)];
            $relation_model = new $relation_class;
            return $this->belongsToMany($relation_class, $this->getCrossTable($relation_model, Str::snake($method)) , $this->entity_name."_id", $relation_model->entity_name."_id");
        }


        if($this->grids && array_key_exists(Str::snake($method), $this->grids)){
            $grid_class=$this->grids[Str::snake($method)];
            return $this->hasMany($grid_class,'instance_id');
        }
        return parent::__call($method, $parameters);

        
    }



       
}