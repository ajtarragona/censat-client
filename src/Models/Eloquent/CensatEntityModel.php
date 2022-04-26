<?php

namespace Ajtarragona\Censat\Models\Eloquent;

use Ajtarragona\Censat\Scopes\CensusScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ajtarragona\Censat\Traits\WithMultipleSort;
use Illuminate\Support\Str;

class CensatEntityModel extends CensatBaseModel
{
    use SoftDeletes;
    use WithMultipleSort;
    
    
    public $entity_name;
    public $census_id;

    protected $hidden = ['cached_values','census_id','entity_id'];
    
    
    

    
    /**
     * Get the table associated with the model.
     * If table is defined, use it
     * Else if entity_name is defined, use it with e_ prefix
     * Else use class_name with e_ prefix
     *
     * @return string
     */
    public function getTable()
    {
        if($this->entity_name){
            $ret="e_".$this->entity_name;
            return $ret;
        }else{
            return parent::getTable();

        }
    }
    
    /**
     * getCrossTable
     * Returns the cross table name with another entity with a given field_name
     * @return void
     */
    public function getCrossTable($related_entity, $rel_field_name)
    {
        return "er_".$this->entity_name ."_".$related_entity->entity_name."_".$rel_field_name;
    }
    
    


    /**
     * Afegeixo el census_id al crear
     */
    public static function create($data)
    {
        $model=new static;
        if($model->census_id) $data=array_merge($data,['census_id'=>$model->census_id]);
        
        //TODO: actualizar la cache de la entidad en remoto

        return parent::create($data);
    }


    
   
    protected static function boot()
    {
        parent::boot();
        $newmodel=new static;
        // if($newmodel->simple_relations){
        //     foreach($newmodel->simple_relations as $rel_name=>$classname){
        //         $newmodel->setRelation($rel_name, $classname);
        //     }
        // }

        // dd($newmodel->relations);
        static::creating(function ($model) use ($newmodel){
            $model->census_id = $newmodel->census_id;
        });
        
        static::addGlobalScope(new CensusScope);
    }      
}