<?php

namespace Ajtarragona\Censat\Models\ApiEloquent;

use Censat;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Traits\ForwardsCalls;

class CensatQueryBuilder{
    
    use ForwardsCalls;
    
    protected $census_name;
    protected $entity_name;

    protected $fields=[];
    protected $filters=[];
    protected $sort;
    protected $direction;
    protected $pagination=false;
    protected $pagesize=10;
    protected $pagename="page";

    protected $model;
    protected $model_class;

    public static $OP_NULL = "is_null";
    public static $OP_NOTNULL = "is_not_null";
    public static $LENGTH_AWARE_PAGINATION = 1;
    public static $SIMPLE_PAGINATION = 2;

    
 
    


    /**
     * Class constructor.
     */
    public function __construct($model)
    {
        $this->census_name = $model->getCensusName();
        $this->entity_name = $model->getEntityName();

        $this->model=$model;
        $this->model_class=get_class($model);
    }


     /**
     * Apply the given scope on the current builder instance.
     *
     * @param  callable  $scope
     * @param  array  $parameters
     * @return mixed
     */
    protected function callScope(callable $scope, $parameters = [])
    {
        array_unshift($parameters, $this);

        // $query = $this->getQuery();

        // We will keep track of how many wheres are on the query before running the
        // scope so that we can properly group the added scope constraints in the
        // query as their own isolated nested where statement and avoid issues.
        // $originalWhereCount = is_null($query->wheres)
        //             ? 0 : count($query->wheres);

        $result = $scope(...array_values($parameters)) ?? $this;

        // if (count((array) $query->wheres) > $originalWhereCount) {
        //     $this->addNewWheresWithinGroup($query, $originalWhereCount);
        // }

        return $result;
    }


    /**
     * Dynamically handle calls into the query instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
       
        if (method_exists($this->model, $scope = 'scope'.ucfirst($method))) {
            return $this->callScope([$this->model, $scope], $parameters);
        }


        //si no existe el método lo reenvio a la coleccion
        // return $this->forwardCallTo($this->get(), $method, $parameters);


    }


    public function noCache(){
        $this->model->setCache(false);
        return $this;
    }

    public function paginate($pagesize=null, $pagename=null){
       
        $this->pagination=self::$LENGTH_AWARE_PAGINATION;
        if($pagesize) $this->pagesize=$pagesize;
        if($pagename) $this->pagename=$pagename;
        return $this;
    }
    
    public function simplePaginate($pagesize=null, $pagename=null){
        $this->pagination=self::$SIMPLE_PAGINATION;
        if($pagesize) $this->pagesize=$pagesize;
        if($pagename) $this->pagename=$pagename;
        return $this;
    }

    public function sortable($pagesize=null){
        $this->pagesize=$pagesize;
        return $this;
    }

    public function count(){
        return $this->get()->count();
    }
    public function select($fields){
        $this->fields=$fields;
        return $this;
    }

    public function whereNull($name){
        $this->addFilter($name, self::$OP_NULL, "dummy");
        return $this;
    }

    public function whereNotNull($name){
        $this->addFilter($name, self::$OP_NOTNULL, "dummy");
        return $this;
    }

    public function where($name, $op_or_value, $value=null){
        $this->addFilter($name, $op_or_value, $value);
        return $this;
    }


    protected function validateOperation($op){
        return true; //TODO
    }


    protected function addFilter($name, $op_or_value, $value=null){
        // dd('addFilter',$this);
        if($value){
            $this->validateOperation($op_or_value);
            if(in_array($op_or_value,[self::$OP_NULL, self::$OP_NOTNULL])){
                $this->filters[]=["id"=>$name, "operation"=>$op_or_value];
            }else{
                $this->filters[]=["id"=>$name, "operation"=>$op_or_value,"value"=>$value];
            }

        }else{
            
            $this->filters[]=["id"=>$name, "value"=>$op_or_value];
            
        }
        return $this;
    }



    protected function getOptionsArray(){
        
        // dd($this);
        $options=[
            "sort" => $this->sort?$this->sort:"id",
            "direction" => $this->direction?$this->direction:"asc",
            "filters" => $this->filters?$this->filters:null,
            "fields" => $this->fields?$this->fields:null,
        ]; 

        if($this->pagination){
            $options["paginate"]=true;
            $options["pagesize"]=$this->pagesize ?? 10;
            $options["page"]=request()->{$this->pagename} ?? 1;
        }
        
        return $options;
    }



    /**
     * Run the query
     */
    public function get(){
        // dd("HOLA");
        // dump($this->getOptionsArray());
        $ret=$this->model->getCachedInstances($this->getOptionsArray());
        return $this->prepareResults($ret);   
 
    }

    //faltarian los métodos no estáticos que ya tengo en la instancia
    
    //update, 
    //get, 
    //set, 
    //add, 
    //remove, 
    //clear, 
    //delete y 
    //destroy


    /* delete queried results */
    public function delete(){
        
    }

    /* Soft deletes queried results */
    public function softDelete(){
        
    }


    protected function prepareResults($results){
        $ret=null;
        // dd($results);
        if($this->pagination==self::$LENGTH_AWARE_PAGINATION){
            // dd($ret);
            $items=($this->model_class)::castAll($results->data);
            // dd($items);
            $ret = new LengthAwarePaginator($items, $results->total, $results->per_page, $results->current_page);
            $ret->setPath(request()->url());
            $ret->setPagename($this->pagename);
        }elseif($this->pagination==self::$SIMPLE_PAGINATION){
            // dd($ret);
            $items=($this->model_class)::castAll($results->data);
            $ret = new Paginator($items, $results->per_page, $results->current_page);
            $ret->setPath(request()->url());
            $ret->setPagename($this->pagename);
        }else{
            $ret=($this->model_class)::castAll($results);
        } 
        return $ret;
    }


    public function sortBy($sort, $direction="asc"){
        $this->sort=$sort?$sort:"id";
        $this->direction=$direction?$direction:"id";
        return $this; 
    }



    public function toJson(){
        return json_encode($this->toArray());
    }


    public function toArray(){
        // dd($this);
        $ret=[
            "census_name" => $this->census_name,
            "entity_name" => $this->entity_name,
            "options" => $this->getOptionsArray(),
        ];
        return $ret;
    }


    public function __toString(){
        return $this->toJson();
    }
   
}