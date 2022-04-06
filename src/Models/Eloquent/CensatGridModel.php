<?php

namespace Ajtarragona\Censat\Models\Eloquent;

use Illuminate\Database\Eloquent\SoftDeletes;

class CensatGridModel extends CensatBaseModel
{
    use SoftDeletes;

    public $entity_name;
    public $grid_name;
    
    protected $hidden = ['instance_id','census_id','entity_id'];
    


    public function getTable()
    {
        return "e_".$this->entity_name ."_g_".$this->grid_name;
    }


  
}