<?php

namespace Ajtarragona\Censat\Models;

use Ajtarragona\Censat\Traits\IsRestClient;
use Ajtarragona\Censat\Models\Entity;
use Ajtarragona\Censat\Models\Census;

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
	

	public function entityFields($short_name, $settings=false){
		return $this->call('GET','entity/'.$short_name.'/fields',[
			'query' => [
				"settings"=> $settings,
			]
		]);
	}


	public function instance($census_name, $entity_name, $id, $options=[]){

		$url='instances/'.$census_name.'/'.$entity_name.'/'.$id;
		if(isset($options["version"])){
			$url.='/version/'.$options["version"];
			unset($options["version"]);
		}

		$ret=$this->call('GET',$url,[
			'query' => $options
		]);	
		return $ret;
	}

	public function instances($census_name, $entity_name, $options=[]){
		$ret=$this->call('GET','instances/'.$census_name.'/'.$entity_name,[
			'query' => $options
		]);	

		return $ret;
		
	}
	

	//TODO

	//tree


	//update
		//update field
		//clear field
		
		//create
		//add related items
		//add grid items
		
		
	//delete
		//remove related item
		//remove grid item

	//destroy



}
