<?php

namespace Ajtarragona\Censat\Models\Eloquent;

use Ajtarragona\Censat\Models\Instance;
use Ajtarragona\Censat\Traits\Castable;
use Ajtarragona\Censat\Traits\Cachable;
use Ajtarragona\Censat\Traits\HasAttributes;
use Ajtarragona\Censat\Traits\HasDates;
use Illuminate\Support\Traits\ForwardsCalls;
use Censat;

class CensatEntity {
    
    use HasDates,
        HasAttributes,
        Castable,
        Cachable,
        ForwardsCalls;

    protected $census_name;
    protected $entity_name;
    // protected $instance;
    
    protected $maps = [];
    protected $images = [];
    protected $selects = [];
    protected $relations = [];
    
   

    public function getCensusName(){ return $this->census_name;}
    public function getEntityName(){ return $this->entity_name;}
    // public function getInstance(){ return $this->instance;}
    // public function setInstance(Instance $instance){ $this->instance = $instance;}



    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->newQuery(), $method, $parameters);
    }


    /**
     * Handle dynamic static method calls into the method.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }
    


    public function newQuery()
    {
        return new CensatQueryBuilder($this);
    }
    

    
   
    public static function all($sort=null, $direction="asc"){
        $query = ((new static)->newQuery())->sortBy($sort, $direction);
        return $query->get();
    }
    

    public static function find($id){
        $query = ((new static)->newQuery())->where('id',$id);
        return $query->get()->first();
    }


    public static function create($attributes=[]){
        $model=(new static);
        $instance = Censat::createInstance($model->census_name, $model->entity_name, $attributes);
        return $model->set($instance);
    }

    
    protected function getInstances($options=[]){
        return Censat::instances($this->census_name, $this->entity_name, $options);       
    }


    public function entity(){
       return Censat::entity($this->entity_name);
    }

    public function census(){
        return Censat::census($this->census_name);
    }
   
    
    public function delete(){
        return Censat::deleteInstance($this->census_name, $this->entity_name, $this->id, true);
    }

    public function softDelete(){
        return Censat::deleteInstance($this->census_name, $this->entity_name, $this->id);
    }
    
    public function save(){
        //garda el objeto
        $attrs=$this->attributeValues();
        // dd($attrs);
        $updated = Censat::updateInstance($this->census_name, $this->entity_name, $this->id, $attrs);
    }
   

}
