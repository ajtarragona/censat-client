<?php

namespace Ajtarragona\Censat\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class CensatSelectModel extends Model
{
    public $entity_name;
    public $field_name;
    public $grid_name;
    
    public $multiple=false;
    
    public $timestamps = false;
    protected $connection= 'censat';
    


    public function getTable()
    {
        return "e_".$this->entity_name ."_s_".$this->field_name;
    }


    public function getCrossTable()
    {
        if($this->multiple){
            $ret = "e_".$this->entity_name;
            
            // si el select multiple esta en una grid
            if($this->grid_name){
                $ret.="e_".$this->entity_name."_g_".$this->grid_name;
            }
             $ret.="_r_".$this->field_name;
        }
    }
}