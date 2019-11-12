<?php

namespace Ajtarragona\Censat\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use Log;
use Exception;
use GuzzleHttp\Exception\ClientException;
use Ajtarragona\Censat\Exceptions\CensatNotFoundException;
use Ajtarragona\Censat\Exceptions\CensatAuthenticationException;
use Ajtarragona\Censat\Exceptions\CensatNotAllowedException;
use Ajtarragona\Censat\Exceptions\CensatAlreadyExistsException;
use Ajtarragona\Censat\Exceptions\CensatIntegrityException;
use Ajtarragona\Censat\Exceptions\CensatSizeLimitException;
use Ajtarragona\Censat\Exceptions\CensatConnectionException;

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

			if($this->debug){
				Log::debug("CENSAT CLIENT: Loggin user {$this->username}");
			}
			
			$response = $this->client->request('POST', "login", [
				'form_params' => [
					"username"=> $this->username,
					"password"=> $this->password,
				],
				'headers' => [
					'Accept'     => 'application/json'
				]
			]);
			if($this->debug) Log::debug($response->getBody());
            $this->token = json_decode($response->getBody())->access_token;
			// dd($this->token);
		}catch(Exception $e){
			$this->parseException($e);
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
			Log::debug("CENSAT: Calling $method to url:" .$this->apiurl."".$url);
			Log::debug("CENSAT: Options:");
			Log::debug($args);
		}
		
	
		
		$ret=false;

		try{
			$response = $this->client->request($method, $url, $args);
			if($this->debug){
				Log::debug("STATUS:".$response->getStatusCode());
				Log::debug("BODY:");
				Log::debug($response->getBody());
			}
			switch($response->getStatusCode()){
				case 200:
				case 201:
				case 204:
					$ret = (string) $response->getBody();
					
					if(isJson($ret)){
						$ret=json_decode($ret);
						//dump($ret);
					}else if(!$ret){
						$ret=true;
					}

					break;
				default: break;
			}

			return $ret;
		} catch (RequestException | ConnectException | ClientException $e) {
			
			$this->parseException($e);
		   
		}
		
	}
	

	private function parseException($e){
		if($this->debug){
			Log::error("Censat API error");
			Log::error($e->getMessage());
		}


		if ($e->hasResponse()) {
			//dd($e->getResponse());
		   $status=$e->getResponse()->getStatusCode();
		   switch($status){
				   case 404:
					throw new CensatNotFoundException(__("Object not found in Censat")); break;
				case 401:
					//Authentication exception
					throw new CensatAuthenticationException(__("User authentication exception")); break;
				case 403:
					//Permissions exception
					throw new CensatNotAllowedException(__("User doesn't have permission")); break;
				case 409: 
					//New name clashes with an existing node in the current parent folder
					throw new CensatAlreadyExistsException(__("Name already exists")); break;
				case 413: 
				case 507: 
					//size limit
					throw new CensatSizeLimitException(__("Size limit exceeded")); break;
				case 422: 
					//name containing invalid characters
					throw new CensatIntegrityException(__("Integrity Exception")); break;
				default: break;
				
		   }
		}
		throw new CensatConnectionException(__("Error connecting to Censat API"));
		
	}

}