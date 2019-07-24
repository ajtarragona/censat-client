<?php

namespace Ajtarragona\Censat\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use Log;
use Exception;
use GuzzleHttp\Exception\ClientException;

trait IsRestClient
{
   
    private function connect(){
		if($this->client && $this->token) return;

		
		if($this->debug) Log::debug("CENSAT: Connecting to API:" .$this->apiurl);


		$this->client = new Client([
			'base_uri' => $this->apiurl,
			'verify' =>false
		]);
		
		// dd($this);
		// dd($this->client);
		try{

			
			$response = $this->client->request('POST', "login", [
				'form_params' => [
					"username"=> $this->username,
					"password"=> $this->password,
				],
				'headers' => [
					'Accept'     => 'application/json'
				]
			]);
            $this->token = json_decode($response->getBody())->access_token;
			// dd($this->token);
		}catch(Exception $e){
			dd($e);
		}
			
	}

	private function call($method, $url, $args=[]){
		$url=ltrim($url,"/");
		if(!$url) return false;

		
		$this->connect();

		//forzar header json
		if(isset($args["headers"])){
			$args["headers"]=array_merge($args["headers"],[
				'Authorization' => 'Bearer ' . $this->token,        
				'Accept'     => 'application/json'
			]);
		}else{
			$args["headers"]=[
				'Authorization' => 'Bearer ' . $this->token,        
				'Accept'     => 'application/json'
			];
		}


		
		if($this->debug){
			Log::debug("CENSAT CLIENT: Calling $method to url:" .$this->apiurl);
			Log::debug("CENSAT CLIENT: Options:");
			Log::debug($args);
		}
		
		// if($method=="PUT"){
		// 	dump("calling $method:".$this->apiUrl($url));
		// 	dump($args);
		// } 
		$ret=false;

		try{
			$response = $this->client->request($method, $url, $args);
			// dump($response->getStatusCode());
			//dump($response);
			//dd((string)$response->getBody());

			switch($response->getStatusCode()){
				case 200:
				case 201:
				case 204:
					//ok
					$ret = (string) $response->getBody();
					//dd($ret);
					

					if(isJson($ret)){
						$ret=json_decode($ret);
						//dump($ret);
					}else if(!$ret){
						$ret=true;
					}

					// if($this->debug){
					// 	Log::debug("CENSAT RESPONSE");
					// 	Log::debug($ret);
					// }
					break;
				default: break;
			}

			return $ret;
		} catch (RequestException | ConnectException | ClientException $e) {

		    dd($e->getMessage());
		    
		   
		}
		
    }

}