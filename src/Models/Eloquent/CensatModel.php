<?php

namespace Ajtarragona\Censat\Models\Eloquent;

use Ajtarragona\Censat\Scopes\CensusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ajtarragona\Censat\Traits\WithMultipleSort;

class CensatModel extends Model
{
    use SoftDeletes;
    use WithMultipleSort;
    
    protected $connection= 'censat';
    
    public $census_id;

    protected $hidden = ['cached_values','census_id','entity_id'];
    
    
    /**
     * Afegeixo el census_id al crear
     */
    public static function create($data)
    {
        $model=new static;
        if($model->census_id) $data=array_merge($data,['census_id'=>$model->census_id]);
        return parent::create($data);
    }

   
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model){
            $newmodel=new static;
            $model->census_id = $newmodel->census_id;
        });
        
        static::addGlobalScope(new CensusScope);
    }      
}