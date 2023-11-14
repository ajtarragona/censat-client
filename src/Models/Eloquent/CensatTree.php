<?php

namespace Ajtarragona\Censat\Models\Eloquent;



use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class CensatTree extends Model
{
    use NodeTrait;

    protected $connection = 'censat';
    protected $table = 'trees';


    public static function getByShortname($short_name)
    {
        return self::where('short_name', $short_name)->first();
    }

    public function scopeWithName($query, $short_name){
        $query->where('short_name',$short_name);
    }


    /** Retorna todos los nodos del arbol */
    public function nodes()
    {
        return  $this->hasMany(CensatTreeNode::class,'tree_id');
    }

    public function node($node_id)
    {
        return $this->nodes()->where('id', $node_id)->first();
    }


    /** Retorna los nodos del arbol hijos del padre pasado */
    public function nodesIn($parent_id = null)
    {
        return  $this->nodes()->childrenOf($parent_id);
    }
}
