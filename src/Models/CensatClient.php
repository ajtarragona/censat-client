<?php

namespace Ajtarragona\Censat\Models;

use Ajtarragona\Censat\Traits\IsRestClient;
use Ajtarragona\Censat\Models\Entity;
use Ajtarragona\Censat\Models\Census;
use function GuzzleHttp\json_encode;
use Ajtarragona\Censat\Exceptions\CensatNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Http\UploadedFile;


class CensatClient {
	
	use IsRestClient;


	protected $options;
	protected $apiurl;
	protected $apiversion;
	protected $username;
	protected $password;
	protected $client;
	protected $token;
	



	public function __construct($options=array()) { 
		$opts=config('censatclient');
		if($options) $opts=array_merge($opts,$options);
		$this->options= json_decode(json_encode($opts), FALSE);

		$this->debug = $this->options->debug;
		$this->apiurl = rtrim($this->options->api_url,"/")."/"; //le quito la barra final si la tiene y se la vuelvo a poner. Asi me aseguro que siempre acaba en barra.
		$this->apiversion = $this->options->api_version ?? 2;
		$this->apiurl.="v".$this->apiversion."/";

		$this->username = $this->options->api_user;
		$this->password = $this->options->api_password;
		$this->client = null;
		$this->token = $this->options->api_token;

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
		$args=[];
		// dd($this->apiversion);
		
		if($this->apiversion==2){
			$args=[
				"settings"=> true,
			];
		}
		$fields=$this->call('GET','entity/'.$short_name.'/fields',[
			'query' => $args
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
		// dd("HOLA",$ret);
		
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
		
		if(!isset($options["paginate"]) && (isset($options["page"]) || isset($options["pagesize"]) ) ){
			$options["paginate"]=true;
		}
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
		if($grid_item_id) $url.='/'.$grid_item_id;


		$ret=$this->call('GET',$url);
		if(is_array($ret)) $ret=collect($ret);

		return $ret;
	}


	private function isUploadedFile($value){
		return $value instanceof UploadedFile;

	}
	
	private function isArrayOfUploadedFiles($value){
		return is_array($value) && $this->isUploadedFile(Arr::first($value));

	}

	private function prepareUploadedFile($key,$file){
		
		if(!$error=$file->getError()){
			return [
				'name' => $key,
				'filename' => $file->getClientOriginalName(),
				'contents' => file_get_contents($file->getRealPath())
			];
			
		}
		return null;
	}
	
	private function isFileArray($value){
		return is_array($value) && array_key_exists("file-name",$value) && array_key_exists("file-content",$value);

	}
	
	private function isArrayOfFileArrays($value){
		return is_array($value) && $this->isFileArray(Arr::first($value));// && array_key_exists("file-name",Arr::first($value)) && array_key_exists("file-content",Arr::first($value));

	}
	
	
	/**
	 * Prepara el array de argumentos, si hay archivos los pasa por multipart, si no por form_params
	 */
	private function prepareArguments($fields){
		$prepared_fields=[];
		// dump($fields);
		if($fields && is_array($fields)){
			$hasfiles=false;

			foreach($fields as $key=>$value){
				if($this->isUploadedFile($value)  ){
					$file=$this->prepareUploadedFile($key,$value);
					if($file){
						$prepared_fields[]=$file;
						$hasfiles=true;
					}
					
				}else if($this->isArrayOfUploadedFiles($value)){
					foreach($value as $uploadedfile){
						$file=$this->prepareUploadedFile($key."[]",$uploadedfile);
						if($file){
							$prepared_fields[]=$file;
							$hasfiles=true;
						}
					}
				}elseif($this->isFileArray($value)  ){
					$prepared_fields[]=[
						'name' => $key,
						'filename' => $value['file-name'],
						'contents' => $value['file-content']
					];
					$hasfiles=true;
				}else if($this->isArrayOfFileArrays($value)){
					foreach($value as $file_array){
						if($this->isFileArray($file_array)){
							$prepared_fields[]=[
								'name' => $key."[]",
								'filename' => $file_array['file-name'],
								'contents' => $file_array['file-content']
							];
							$hasfiles=true;
						}
					}
				}else if(is_array($value)){
					foreach($value as $i=>$v){
						$prepared_fields[]=[
							'name' => $key."[".$i."]",
							'contents' => $v
						];
					}
				}else{
					// $normal_fields[$key]=$value;
					
					$prepared_fields[]=[
						'name' => $key,
						'contents' => $value
					];
					
				}
			}

			
			if($hasfiles){
				$ret= [
					'multipart' => $prepared_fields
				];
			}else{
				$ret= [
					'json' => $fields
				];
			}
			
			// dd($ret);
			return $ret;
		}
		return $prepared_fields;

	}

	
	//create instance
	public function createInstance($census_name, $entity_name, $fields){
		
		$url='instances/'.$census_name.'/'.$entity_name;
		
		// dd($this->prepareArguments($fields));
		$ret=$this->call('POST',$url, $this->prepareArguments($fields) );
			

		if($ret) return new Instance($census_name, $entity_name, $ret);
		return false;

	}
		
	


	//update instance
	public function updateInstance($census_name, $entity_name, $id, $fields){
		
		// dd($fields);
		$url='instances/'.$census_name.'/'.$entity_name.'/'.$id;
		
		
		$args=$this->prepareArguments($fields) ;
		// dd($args);
		$ret=$this->call('POST',$url, $args);

		if($ret) return new Instance($census_name, $entity_name, $ret);
		return false;

	}
		

	

	//delete instance
	public function deleteInstance($census_name, $entity_name, $id, $hard=false){
		$url='instances/'.$census_name.'/'.$entity_name.'/'.$id;
		$args=[];
		
		if($hard){
			
			$args=[
				'form_params' =>[
					'destroy' => true
				]
			];
		}
		// dd($args);
		$ret=$this->call('DELETE',$url, $args);

		return $ret;
	}	
	

	
	

	//update field
	public function updateInstanceField($census_name, $entity_name, $id, $field_name, $value){
		// dd("HOLA");
		$url='instances/'.$census_name.'/'.$entity_name.'/'.$id.'/'.$field_name;
		// dd($value);
		$args=$this->prepareArguments(['value'=> $value]);
		// dd($args);
		$ret=$this->call('POST',$url, $args);
		// [
		// 	'form_params' => [
		// 		"value" => $value
		// 	]
		// ]);

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
	public function addInstanceFieldItem($census_name, $entity_name, $id, $field_name, $values){
		if(is_array($values) && !$this->isFileArray($values)){
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
		$url='instances/'.$census_name.'/'.$entity_name.'/'.$id.'/'.$grid_name.'/add';

		$ret=$this->call('POST',$url, $this->prepareArguments($values) );

		if($ret) return new Instance($census_name, $entity_name, $ret);
		return false;

	}	

	//update grid item
	public function updateInstanceGridItem($census_name, $entity_name, $id, $grid_name, $grid_item_id, $values=[]){
		$url='instances/'.$census_name.'/'.$entity_name.'/'.$id.'/'.$grid_name.'/'.$grid_item_id;
		
		$ret=$this->call('POST',$url, $this->prepareArguments($values));

		if($ret) return new Instance($census_name, $entity_name, $ret);
		return false;

	}


	
	
	public function removeInstanceGridItem($census_name, $entity_name, $id, $grid_name, $grid_item_id){
		return $this->removeInstanceFieldItem($census_name, $entity_name, $id, $grid_name, $grid_item_id);
	}




	/* RELATED */
	//add related items
	public function addInstanceRelatedItem($census_name, $entity_name, $id, $field_name, $item_id){
		if($this->isFileArray($item_id)){
			$url='instances/'.$census_name.'/'.$entity_name.'/'.$id.'/'.$field_name.'/add';
			$args=$this->prepareArguments(["value"=>$item_id]);
			$ret=$this->call('POST',$url, $args);

		}else{
			$url='instances/'.$census_name.'/'.$entity_name.'/'.$id.'/'.$field_name.'/add/'.$item_id;
			$ret=$this->call('POST',$url);
		}

		if($ret) return new Instance($census_name, $entity_name, $ret);
		return false;

	}

	//remove related item
	public function removeInstanceRelatedItem($census_name, $entity_name, $id, $field_name, $item_id){
		return $this->removeInstanceFieldItem($census_name, $entity_name, $id, $field_name, $item_id);

		
		

	}
	


	public function cache($census_name,$entity_name,$instance_id=null){
		$url='cache/'.$census_name."/".$entity_name;
		if($instance_id) $url.="/".$instance_id;

		return $this->call('POST',$url);	

	}




	public function getAttachment($attachment_id){
		$url='attachment/'.$attachment_id;
		return $this->call('GET',$url);	
	}
	
	public function getAttachmentContent($attachment_id){
		$url='attachment/'.$attachment_id.'/content';
		return $this->call('GET',$url);	
	}

	public function downloadAttachment($attachment_id){
		$doc=$this->getAttachment($attachment_id);
		if($doc){
			$content= $this->getAttachmentContent($attachment_id);
			$content=base64_decode($content);
			return response($content)->withHeaders([
				'Content-disposition' => 'attachment; filename=' . $doc->name,
				'Access-Control-Expose-Headers' => 'Content-Disposition',
				'Content-Type' => $doc->mimetype,
			]);
		}
		abort(500, "Error downloading file");

	}





	/** TREES FEATURE */

	public function getTrees($options=[]){
		$ret=$this->callFeature('GET','census/trees','/',[
			'query' => $options
		]);	
		return Tree::cast($ret);
	}

	

	public function getTree($tree_id, $options=[]){
		// $url='census/trees/'.$tree_id;
		$ret=$this->callFeature('GET','census/trees',$tree_id, [
			'query' => $options
		]);	
		return Tree::cast($ret);
	}

	public function getTreeNodes($tree_id, $options=[]){
		$ret=$this->callFeature('GET','census/trees',$tree_id.'/children',[
			'query' => $options
		]);	
		return TreeNode::cast($ret);
	}

	public function getNode($tree_id, $node_id, $options=[]){
		// $url='census/trees/'.$tree_id;
		$ret=$this->callFeature('GET','census/trees', $tree_id.'/node/'.$node_id , [
			'query' => $options
		]);	
		return TreeNode::cast($ret);
	}


	public function getNodeChildren($tree_id, $node_id, $options=[]){
		// $url='census/trees/'.$tree_id;
		$ret=$this->callFeature('GET','census/trees',$tree_id.'/node/'.$node_id.'/children', [
			'query' => $options
		]);	
		return TreeNode::cast($ret);
	}
	public function getNodeParent($tree_id, $node_id, $options=[]){
		// $url='census/trees/'.$tree_id;
		$ret=$this->callFeature('GET','census/trees',$tree_id.'/node/'.$node_id.'/parent', [
			'query' => $options
		]);	
		return TreeNode::cast($ret);
	}

	public function getNodeAncestors($tree_id, $node_id, $options=[]){
		// $url='census/trees/'.$tree_id;
		$ret=$this->callFeature('GET','census/trees',$tree_id.'/node/'.$node_id.'/ancestors', [
			'query' => $options
		]);	
		return TreeNode::cast($ret);
	}
	public function getNodeDescendants($tree_id, $node_id, $options=[]){
		// $url='census/trees/'.$tree_id;
		$ret=$this->callFeature('GET','census/trees',$tree_id.'/node/'.$node_id.'/descendants', [
			'query' => $options
		]);	
		return TreeNode::cast($ret);
	}

	public function getNodeSiblings($tree_id, $node_id, $direction=null, $options=[]){
		// $url='census/trees/'.$tree_id;
		$ret=$this->callFeature('GET','census/trees',$tree_id.'/node/'.$node_id.'/siblings'.($direction?('/'.$direction):''), [
			'query' => $options
		]);	
		return TreeNode::cast($ret);
	}

}
