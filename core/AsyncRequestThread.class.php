<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * The AsyncRequestThread implements a runnable class 
 * that execute an asynchronous client request call on a web service.
 * 
 * @version 1.0
 * @since 2014-09-21
 */
class AsyncRequestThread extends Thread{
  
       private $asyncRequestId;
       private $calcFusionClient;
       private $servicePath;
       private $method;
       private $parameters;
	
	/**
	 * Constructor for AsyncRequestService
	 * 
	 * @param string $asyncRequestId A unique request id to be use as a reference for later retrieval
	 * @param string $calcFusionClient Refers to CalcFusionClient to use for the request service call
	 * @param string $servicePath Refers to the path of the web service to call
	 * @param string $method GET/POST method
	 * @param array $parameters The form/query parameters to pass
	 */
    public function __construct($asyncRequestId, CalcFusionClient $calcFusionClient,
        $servicePath,  $method, array $parameters){     
        
    	$this->asyncRequestId = $asyncRequestId;
    	$this->calcFusionClient = $calcFusionClient;
    	$this->servicePath = $servicePath;
    	$this->method = $method;
    	$this->parameters = $parameters;
    }
    
	
	public function  run() {
            $result = " none ";
            try{
    		$result = $this->calcFusionClient->requestService($this->servicePath, 
                        $this->method, $this->parameters);
            }catch(Exception $e){}

            // call the function asyncCallbackResult of the CalcFusionClient to store
            //  the result reference by asyncRequestId
            $this->calcFusionClient->asyncCallbackResult($this->asyncRequestId, $result);
	}
       
}
