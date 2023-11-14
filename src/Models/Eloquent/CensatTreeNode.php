<?php

namespace Ajtarragona\Censat\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class CensatTreeNode extends Model
{
    use NodeTrait;

    protected $connection = 'censat';
    protected $table = 'tree_nodes';
    

    public function tree(){
        return $this->belongsTo(CensatTree::class,'tree_id');
    }
    
    
    public function scopeOfTree($query, $tree_id){
        if(is_numeric($tree_id)){
            $query->where('tree_id',$tree_id);
        }else{
            $query->whereHas('tree',function($query) use($tree_id){
                $query->withName($tree_id);
            });
        }
    }
  
    
    public function scopeOfCensus($query, $census_id){
        $query->where('census_id',$census_id);
    }
    public function scopeOfEntity($query, $entity_id){
        $query->where('entity_id',$entity_id);
    }

    public function scopeChildrenOf($query, $parent_id=null){
        if($parent_id){
            $query->where('parent_id',$parent_id);
        }else{
            $query->whereIsRoot();
        }
    }

    public function scopeOfInstance($query, $instance_id){
        $query->where('instance_id',$instance_id);
    }
    public function scopeWithTerm($query, $term, $hyerarchical=false){
        $query->where(function($query) use($term, $hyerarchical){
            $query->where('name','like','%'.$term.'%');
            if($hyerarchical){
              
                $query->orWhereHas('parent', function ($query) use($term) { 
                    $query->where('name','like','%'.$term.'%');
                });
              
            }
        });
    }


    /** Retorna la numeració completa, amb la dels seus pares */
    public function getFullNumber(){
        $tree=$this->tree;
        $ret="";
        if($this->parent_id ){
            $ret.= $this->parent->getFullNumber(). $tree->num_separator;
        }
        $ret.= $tree->num_start + $this->getPosition();
        return $ret;
    }

    /** retorna la posició del node al seu nivell */
    public function getPosition(){
        // SELECT COUNT(*) as `position` FROM `nodes` WHERE `parent_node_id` = ? AND `left` < ?
        return self::newQuery()->where('tree_id', $this->tree_id)->where('parent_id', $this->parent_id)->where('_lft','<',$this->_lft)->count();

    }

}