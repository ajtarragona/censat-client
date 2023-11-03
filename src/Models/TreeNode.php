<?php

namespace Ajtarragona\Censat\Models; 

use Ajtarragona\Censat\Traits\SimpleCastable;
use Censat;

class TreeNode{

    use SimpleCastable;
    
    public $id;
    public $tree_id;
    public $children;
    
    public $name;
    public $census_id;
    public $entity_id;
    public $instance_id;
    public $census_name;
    public $entity_name;
    public $icon;
    
    
    public function __construct($args=null)
    {
        if($args && is_object($args)){
           
            foreach($args as $key=>$value){
                if($key=="children"){
                    $child=[];
                    if($value){
                        foreach($value as $v){
                            $child[]=new self($v);
                        }
                        $this->children=$child;
                    }
                }else{
                    $this->$key=$value;
                }
            }
        }
        
    }

    public function parent($options=[]){
        return Censat::getNodeParent($this->tree_id, $this->id, $options);
    }
    public function siblings($direction=null, $options=[]){
        return Censat::getNodeSiblings($this->tree_id, $this->id, $direction, $options);
    }
    public function descendants($options=[]){
        return Censat::getNodeDescendants($this->tree_id, $this->id, $options);
    }
    public function ancestors($options=[]){
        return Censat::getNodeAncestors($this->tree_id, $this->id, $options);
    }
    public function children($options=[]){
        return Censat::getNodeChildren($this->tree_id, $this->id, $options);
    }
   

}