<?php

namespace Ajtarragona\Censat\Models;

use Ajtarragona\Censat\Traits\IsRestClient;
use Ajtarragona\Censat\Models\Entity;
use Ajtarragona\Censat\Models\Census;
use function GuzzleHttp\json_encode;
use Ajtarragona\Censat\Exceptions\CensatNotFoundException;

class CensatClient {
	
	use IsRestClient;


	protected $options;
	protected $apiurl;
	protected $username;
	protected $password;
	protected $client;
	protected $token;
	



	public function __construct($options=array()) { 
		$opts=config('censatclient');
		if($options) $opts=array_merge($opts,$options);
		$this->options= json_decode(json_encode($opts), FALSE);

		$this->debug = $this->options->debug;
		$this->apiurl = $this->options->api_url;
		$this->username = $this->options->api_user;
		$this->password = $this->options->api_password;
		$this->client = null;
		$this->token = null;


	}



	public function censuses(){
		$ret=$this->call('GET','census');
		return Census::cast($ret);
	}


	public function census($short_name){
		$ret=$this->call('GET','census/'.$short_name);
		return Census::cast($ret);
	}



	public function censusEntities($short_name){
		$ret=$this->call('GET','entity/from/'.$short_name);		
		return Entity::cast($ret);
	}



	public function entities(){
		$ret=$this->call('GET','entity');
		return Entity::cast($ret);
	}



	public function entity($short_name){
		$ret=$this->call('GET','entity/'.$short_name);	
		return Entity::cast($ret);
	}
	


	public function entityFields($short_name){
		$fields=$this->call('GET','entity/'.$short_name.'/fields',[
			'query' => [
				"settings"=> true,
			]
		]);
		if($fields){
			 $ret=collect();
			 foreach($fields as $key=>$field){
				 $ret->put($key, new Field( $short_name, $key, $field));
			 }
			 return $ret;
		}
		return null;

		
	}



	public function entityField($entity_name, $field_name){
		$ret=$this->call('GET','entity/'.$entity_name.'/fields/'.$field_name,[
			'query' => [
				"settings"=> true,
			]
		]);
		// dump($ret);
		return new Field( $entity_name, $field_name, $ret);
	}




	public function entityGridFields($short_name, $grid_name){
		$fields=$this->call('GET','entity/'.$short_name.'/fields/'.$grid_name.'/fields',[
			'query' => [
				"settings"=> true,
			]
		]);
		if($fields){
			 $ret=collect();
			 foreach($fields as $key=>$field){
				 $ret->put($key, new Field( $short_name, $key, $field));
			 }
			 return $ret;
		}
		return null;

		
	}

	



	//Get instance
	public function instance($census_name, $entity_name, $id, $options=[]){

		$url='instances/'.$census_name.'/'.$entity_name.'/'.$id;
		if(isset($options["version"])){
			$url.='/version/'.$options["version"];
			unset($options["version"]);
		}

		$ret=$this->call('GET',$url,[
			'query' => $options
		]);
		if($ret) return new Instance($census_name, $entity_name, $ret);

		return $ret;
	}




	private  function castTree( $census_name,$entity_name, $array ){
        if($array && $census_name){
            $ret=collect();
            foreach($array as $node){
                $instance=new Instance($census_name, $entity_name, $node) ;
                if(isset($node->children)){
                    $instance->children = $this->castTree($census_name,$entity_name,$node->children);
                }
                $ret->push( $instance);
            }
            return $ret;
        }
    }




	public function instancesTree($census_name, $entity_name, $field_name, $options=[]){
		if(isset($options["filters"]) ) $options["filters"]=json_encode($options["filters"]);

		// dd($options);

		$tree=$this->call('GET','tree/'.$census_name.'/'.$entity_name.'/'.$field_name,[
			'query' => $options
		]);	
		// return $tree;

		if($tree){
			if(is_object($tree)){
				throw new CensatNotFoundException( $tree->error); 
			}else{
				return $this->castTree($census_name,$entity_name,$tree);
			}
		}
        return $tree;
		
	}


	public function search($census_name, $entity_name, $filters=[], $options=[]){

		$options["filters"] = $filters;
		return $this->instances($census_name, $entity_name, $options);

	}

	public function instances($census_name, $entity_name, $options=[]){
		if($options && !is_array($options)) $options=[];

		if(isset($options["filters"]) ) $options["filters"] = json_encode($options["filters"]);

		$array=$this->call('GET','instances/'.$census_name.'/'.$entity_name,[
			'query' => $options
		]);	
		
		if(isset($options["paginate"]) && $options["paginate"] && is_object($array)){
			$data=collect();
			foreach($array->data as $node){
				$data->push(new Instance($census_name, $entity_name, $node) );	
			}
			$array->data=$data;
			unset($array->first_page_url);
			unset($array->last_page_url);
			unset($array->next_page_url);
			unset($array->prev_page_url);
			unset($array->path);
			return $array;
		}
		$ret=collect();
		foreach($array as $node){
			$ret->push(new Instance($census_name, $entity_name, $node) );
		}
		
		return $ret;
		
	}
	


	//get instance field
	public function getInstanceField($census_name, $entity_name, $id, $field_name, $grid_item_id=null){

		$url='instances/'.$census_name.'/'.$entity_name.'/'.$id.'/'.$field_name;
		if($grid_id) $url.='/'.$grid_id;


		$ret=$this->call('GET',$url);
		if(is_array($ret)) $ret=collect($ret);

		return $ret;
	}



	
	//create instance
	public function createInstance($census_name, $entity_name, $fields){
		
		$url='instances/'.$census_name.'/'.$entity_name;
		
		$ret=$this->call('POST',$url,[
			'query' => $fields
		]);
			

		if($ret) return new Instance($census_name, $entity_name, $ret);
		return false;

	}
		
	
	//update instance
	public function updateInstance($census_name, $entity_name, $id, $fields){
		
		$url='instances/'.$census_name.'/'.$entity_name.'/'.$id;
		
		$ret=$this->call('PUT',$url,[
			'query' => $fields
		]);
			

		if($ret) return new Instance($census_name, $entity_name, $ret);
		return false;

	}
		

	

	//delete instance
	public function deleteInstance($census_name, $entity_name, $id, $hard=false){
		$url='instances/'.$census_name.'/'.$entity_name.'/'.$id;
		$args=[];
		
		if($hard){
			$args=[
				'destroy' => true
			];
		}

		$ret=$this->call('DELETE',$url, $args);

		return $ret;
	}	
	

	
	

	//update field
	public function updateInstanceField($census_name, $entity_name, $id, $field_name, $value){
		$url='instances/'.$census_name.'/'.$entity_name.'/'.$id.'/'.$field_name;
		$ret=$this->call('PUT',$url,[
			'query' => [
				"value" => $value
			]
		]);

		if($ret) return new Instance($census_name, $entity_name, $ret);
		return false;

	}
	
	//clear field
	public function clearInstanceField($census_name, $entity_name, $id, $field_name){
		$url='instances/'.$census_name.'/'.$entity_name.'/'.$id.'/'.$field_name;
		$ret=$this->call('DELETE',$url);
		if($ret) return new Instance($census_name, $entity_name, $ret);
		return false;

	}

	//add instance field item
	public function addInstanceItem($census_name, $entity_name, $id, $field_name, $values){
		if(is_array($values)){
			return $this->addInstanceGridItem($census_name, $entity_name, $id, $field_name, $values);
		}else{
			return $this->addInstanceRelatedItem($census_name, $entity_name, $id, $field_name, $values);
		}
	}


	//remove grid item
	public function removeInstanceFieldItem($census_name, $entity_name, $id, $field_name, $item_id){
		$url='instances/'.$census_name.'/'.$entity_name.'/'.$id.'/'.$field_name.'/'.$item_id;
		$ret=$this->call('DELETE',$url);
		
		if($ret) return new Instance($census_name, $entity_name, $ret);
		return false;
		
	}	

	/* GRIDs */

	//get grid items
	public function getInstanceGridItems($census_name, $entity_name, $id, $grid_name){
		return $this->getInstanceField($census_name, $entity_name, $id, $grid_name);
	}

	//get grid item
	public function getInstanceGridItem($census_name, $entity_name, $id, $grid_name, $grid_item_id){
		$ret=$this->getInstanceField($census_name, $entity_name, $id, $grid_name, $grid_item_id);
		if($ret && $ret->count()>0) return $ret->first();
		return null;
	}
	
	

	//add grid items
	public function addInstanceGridItem($census_name, $entity_name, $id, $grid_name, $values=[]){
		$url='instances/'.$census_name.'/'.$entity_name.'/'.$id.'/'.$grid_name;
		$ret=$this->call('POST',$url,[
			'query' => $values
		]);

		if($ret) return new Instance($census_name, $entity_name, $ret);
		return false;

	}	

	//update grid item
	public function updateInstanceGridItem($census_name, $entity_name, $id, $grid_name, $grid_item_id, $values=[]){
		$url='instances/'.$census_name.'/'.$entity_name.'/'.$id.'/'.$grid_name.'/'.$grid_item_id;
		$ret=$this->call('PUT',$url,[
			'query' => $values
		]);

		if($ret) return new Instance($census_name, $entity_name, $ret);
		return false;

	}


	
	
	public function removeInstanceGridItem($census_name, $entity_name, $id, $grid_name, $grid_item_id){
		return $this->removeInstanceFieldItem($census_name, $entity_name, $id, $grid_name, $grid_item_id);
	}




	/* RELATED */
	//add related items
	public function addInstanceRelatedItem($census_name, $entity_name, $id, $field_name, $item_id){
		$url='instances/'.$census_name.'/'.$entity_name.'/'.$id.'/'.$field_name.'/'.$item_id;
		$ret=$this->call('POST',$url);

		if($ret) return new Instance($census_name, $entity_name, $ret);
		return false;

	}

	//remove related item
	public function removeInstanceRelatedItem($census_name, $entity_name, $id, $field_name, $item_id){
		return $this->removeInstanceFieldItem($census_name, $entity_name, $id, $field_name, $item_id);

		
		

	}
	




	



}
