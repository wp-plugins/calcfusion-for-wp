<?php

use Httpful\Mime;
/*
 * composer autoload
 */
require __DIR__.DIRECTORY_SEPARATOR.'vendor/autoload.php';

/*
 * autoload functions does not work inside threads so we need to 
 * include them here
 */

require_once 'Oauth2ClientUtils.class.php';
require_once 'ClientTokenGenerator.class.php';
/**
 * The CalcFusionClient class provides methods for processing 
 * OAuth2.0 authentication requests and web service requests.
 * 
 * @version 1.0.1
 * @since 2014-09-22
 *
 */
class CalcFusionClient  {
        
    const CONTEXTINFO_PARAM = 'cfxlContextInfo';
	const CONTEXT = 'PHP';
	const VERSION = '1.0.1';
	const FILENAME = 'calcfusion-api-1.0.1.phar';

	const RESPONSE_1010 = 1010;
	const RESPONSE_1010_MSG = 'Username was not specified. (CF:1010)';
	
	const RESPONSE_1011 = 1011;
	const RESPONSE_1011_MSG = 'Account ID was not specified. (CF:1011)';
	
	const RESPONSE_1012 = 1012;
	const RESPONSE_1012_MSG = 'Appkey was not specified. (CF:1012)';
	
	const RESPONSE_1013 = 1013;
	const RESPONSE_1013_MSG = 'Undefined access token. Client service needs to be logged in first. (CF:1013)';
	
	const RESPONSE_2004 = 2004;
	const RESPONSE_2004_MSG = 'Web service host URL was not specified. (CF:2004)';
	
	const RESPONSE_2005 = 2005;
	const RESPONSE_2005_MSG = 'Web service path was not specified. (CF:2005)';
	
	const RESPONSE_2006 = 2006;
	const RESPONSE_2006_MSG = 'Undefined method type. Method type should be POST or GET. (CF:2006)';
	
	const RESPONSE_2007 = 2007;
	const RESPONSE_2007_MSG = ' method type is not managed by the web service. (CF:2007)';
	
	const RESPONSE_2008 = 2008;
	const RESPONSE_2008_MSG = ' media type format is not managed by the web service. The web service only return JSON format. (CF:2008)';
	
	const RESPONSE_400 = 'Bad Request. The request cannot be fulfilled or was invalid.';
	const RESPONSE_401 = 'Unauthorized. Authentication has failed or an access token has not been provided.';
	const RESPONSE_403 = 'Forbidden. The request was valid, but access was denied. User has no access rights with the requested service.';
	const RESPONSE_404 = 'Not Found. The requested URL or web service was not found.';
	const RESPONSE_405 = 'Method Not Allowed. A request was made of a page using a request method not supported by that page.';
	const RESPONSE_406 = 'Not Acceptable. The server can only generate a response that is not accepted by the client.';
	const RESPONSE_407 = 'Proxy Authentication Required. You must authenticate with a proxy server before this request can be served.';
	const RESPONSE_408 = 'Request Timeout. The request took longer than the server was prepared to wait.';
	const RESPONSE_409 = 'Conflict. The request could not be completed because of a conflict.';
	const RESPONSE_410 = 'Gone. The requested page is no longer available.';
	const RESPONSE_411 = 'Length Required. The \'Content-Length\' is not defined. The server will not accept the request without it.';
	const RESPONSE_412 = 'Precondition Failed. The precondition given in the request evaluated to false by the server.';
	const RESPONSE_413 = 'Request Entity Too Large. The server will not accept the request, because the request entity is too large.';
	const RESPONSE_414 = 'Request-url Too Long. The server will not accept the request, because the url is too long. Occurs when you convert a \'POST\' request to a \'GET\' request with a long query information.';
	const RESPONSE_415 = 'Unsupported Media Type. The server will not accept the request, because the media type is not supported.';
	const RESPONSE_417 = 'Expectation Failed. The server cannot meet the requirements of the Expect request-header field.';
	const RESPONSE_500 = 'Internal Server Error. The request was not completed. The server encountered an internal error.';
	const RESPONSE_501 = 'Not Implemented. The server either does not recognize the request method, or it lacks the ability to fulfill the request.';
	const RESPONSE_502 = 'Bad Gateway. The server was acting as a gateway or proxy and received an invalid response from the upstream server.';
	const RESPONSE_503 = 'Service Unavailable. The server or requested service is unavailable.';
	const RESPONSE_504 = 'Gateway Timeout. The server was acting as a gateway or proxy and did not receive a timely response from the upstream server.';
	const RESPONSE_505 = 'HTTP Version Not Supported. The server does not support the HTTP protocol version used in the request.';
	const RESPONSE_507 = 'Insufficient Storage. There is not enough space/storage to complete the request.';
        
    protected  $restClient;
	protected  $url;
	protected  $clientTokenInfo;
	protected  $appKey;
	
        /**
	 * Holds the returned results of asynchronous client request
	 */
	private $resultStorage;
        
	/**
	 * Constructor for CalcFusionClient
	 * @param string $url The URL path of the target web service provider
	 */
	public function __construct($url){
		$this->url = $url;
// 		try{
// 			 $this->resultStorage=new ThreadSafeArray();
// 		}catch (Exception $ex){
			$this->resultStorage=array();
//         }    
               
	}
        
    /**
    * implementation for method that is executed when calling a method that does not exist;
    * we are using this method to implement method overloading by number of parameters
    * 
    */
	public function __call($name, $args) {
		$method = $name."_".count($args);
		if (!method_exists($this,$method)) {
			throw new Exception("Call to undefined method ".get_class($this)."::$method");
		}
		return call_user_func_array(array($this,$method),$args);
	}
  
	/**
	 * This method manages the client authentication process and gets an Access Token
	 * from the response.
	 *
	 * @param string $username The Client User Name
	 * @param string $password The Client User Password
	 * @param string $accountId The Client Account ID
	 * @param string $appkey The Client App Key
	 * @return string A valid Access Token if authentication is valid, otherwise it returns an error message
	 *
	 */
	public function login_4($username, $password, $accountId, $appkey){
		return $this->login($username, $password, $accountId, $appkey, array());
	}
          
	/**
	* This method manages the client authentication process and gets an Access Token 
	* from the response.
	* 
	* @param string $username The Client User Name
	* @param string $password The Client User Password
	* @param string $accountId The Client Account ID
	* @param string $appkey The Client App Key
	* @param array $parameters Additional query parameters to pass
	* @return string A valid Access Token if authentication is valid, otherwise it returns an error message 
	* 
	*/
	public function login_5($username, $password, $accountId, $appkey, array $parameters){				
		$errorObj = null;
		if(!$this->url){
			$errorObj = $this->createErrorObj(CalcFusionClient::RESPONSE_400, CalcFusionClient::RESPONSE_2004_MSG);
		}
		else if(!$username){
                    $errorObj = $this->createErrorObj(CalcFusionClient::RESPONSE_400, CalcFusionClient::RESPONSE_1010_MSG);
		}
		else if(!$accountId){
                     $errorObj = $this->createErrorObj(CalcFusionClient::RESPONSE_400, CalcFusionClient::RESPONSE_1011_MSG);
		}
		else if(!$appkey){
                    $errorObj = $this->createErrorObj(CalcFusionClient::RESPONSE_400, CalcFusionClient::RESPONSE_1012_MSG);
		}
		
		if($errorObj != null){
			return json_encode($this->createErrorResponse($errorObj));
		}
		
		$requestId = Oauth2ClientUtils::getUniqueId();
		if(!$parameters || !is_array($parameters)){
			$parameters = array();
		}
		
		$parameters["username"] = $username;
		$parameters["password"] = $password;
		$parameters["accountId"] = $accountId;
		$parameters["appkey"] = $appkey;
		$parameters[CalcFusionClient::CONTEXTINFO_PARAM] = $this->getContextInfoParam();
		$parameters["requestId"] = $requestId;
		$host = $this->url."/token?".http_build_query($parameters); 
		$response = \Httpful\Request::get($host)                  
                      ->sendsJson()                                                  
                      ->expects(\Httpful\Mime::JSON)
                      ->timeout(60)
                      ->send();                 
                
		$status = $response->code;
		$jsonResponse=json_decode($response, true);
                
		if ($status != 200){
			return json_encode($this->createErrorResponse($this->createStatusResponse($status)));
		}                  
		else {
			$responseData= $jsonResponse;
			$resultResponseStr=$responseData["response"];
			$resultResponseStatus=isset($resultResponseStr["status"])?$resultResponseStr["status"]:false;
                        
			if($resultResponseStatus && strtoupper($resultResponseStatus)=='OK'){
				$clientInfoStr = $responseData["data"];
				$this->clientTokenInfo = ClientAccessTokenInfo::getObjFromAssArray($clientInfoStr);
				$this->appKey = $appkey;
			}
		}
		return json_encode($jsonResponse);
	}
	
    /**
	 * This method manages the refresh of the Access Token
     * base on the accessToken of the pass parameters. 
	 * If accessToken property on pass parameters is null, 
     * then the accessToken in the clientTokenInfo will be use. 
	 * @param array $parameters
	 * @return string A new Access Token if it has been successfully refreshed, 
	 * otherwise it returns an error message
	 * 
	 */
	public function refreshToken(array $parameters=null){
		
		if(!$parameters || !is_array($parameters)){
			$parameters=array();
		}
		
		if (!isset($parameters["accessToken"])){
			$parameters["accessToken"]=$this->clientTokenInfo->getAccessToken();
		}
		
		$result = $this->requestService("token/refresh", "GET", $parameters);
                
		$resultData=  json_decode($result, true);                
		$responseStr=  $resultData["response"];
		$responseStatus=$responseStr["status"];
                
		if (strtoupper($responseStatus)=="OK"){
			$clientInfoStr=$resultData["data"];
			$this->clientTokenInfo = ClientAccessTokenInfo::getObjFromAssArray($clientInfoStr);
		}
		
		return $result;
	}
        
    /**
	 * This method logout the client from the server and invalidates it existing Access Token. 
	 * 
	 * @return string Response Status in JSON String format
	 * 
	 */
	public function logout()  {		
		return $this->requestService("token/logout", "GET", array());
	}
               
        /**
	 * This method manages the client request call for a web service. 
	 * 
	 * @param string $servicePath Refers to the path of the web service to call
	 * @param string $method GET/POST method
	 * @param array $parameters The form/query parameters to pass
	 * @return string JSON String format of the Response Data
	 * 
	 */
	public function requestService($servicePath, $method, array $parameters){			
            
            $errorObj = null;
            if(!$this->clientTokenInfo){
                $errorObj = $this->createErrorObj(CalcFusionClient::RESPONSE_401,
                        CalcFusionClient::RESPONSE_1013_MSG);			
            }
            
            if(!$this->url){
                $errorObj = $this->createErrorObj(CalcFusionClient::RESPONSE_400,
                        CalcFusionClient::RESPONSE_2004_MSG);			
            }
            
            if(!$servicePath){
                $errorObj = $this->createErrorObj(CalcFusionClient::RESPONSE_400,
                        CalcFusionClient::RESPONSE_2005_MSG);			
            }
            
            else if(!$this->appKey){
            	$errorObj = $this->createErrorObj(CalcFusionClient::RESPONSE_400, CalcFusionClient::RESPONSE_1012_MSG);
            }
            
             if(!$method){
                $errorObj = $this->createErrorObj(CalcFusionClient::RESPONSE_405,
                        CalcFusionClient::RESPONSE_2006_MSG);			
            } else if (!in_array(strtoupper($method), array("GET", "POST", "DOWNLOAD")))  {
                  $errorObj = $this->createErrorObj(CalcFusionClient::RESPONSE_406,
                        CalcFusionClient::RESPONSE_2007_MSG);
            }          		
		
            if($errorObj != null){
                return json_encode($this->createErrorResponse($errorObj, $parameters));                  
            }
		
            $requestId = Oauth2ClientUtils::getUniqueId();
            $accessToken=$this->clientTokenInfo->getAccessToken();
            $requestDate=Oauth2ClientUtils::getFormattedDate();
            $newAccessToken=ClientTokenGenerator::createNewAccessToken($accessToken, $requestDate, $this->appKey); 
            if (substr($servicePath, 0,1)!="/"){
                $servicePath="/".$servicePath;
            }
            $host=$this->url.$servicePath;                 
            $parameters[CalcFusionClient::CONTEXTINFO_PARAM]= $this->getContextInfoParam();
            $parameters["requestId"]=$requestId;
         
            $method=strtoupper($method);

            if ($method=="GET"){
                $host.= "?".http_build_query($parameters); 
                  $response = \Httpful\Request::get($host)                  
                        ->sendsJson()                              
                        ->authenticateWith($accessToken, $newAccessToken)             
                        ->addHeaders(array(
                            'DATE' => $requestDate,              
                        ))
						->expects(\Httpful\Mime::JSON)
                          ->timeout(60)
                        ->send();
            }else if ($method=="POST"){
                   $response = \Httpful\Request::post($host)                  
                        ->sendsType(Mime::FORM)                              
                        ->authenticateWith($accessToken, $newAccessToken)  
                        ->body($parameters)            
                        ->addHeaders(array(
                            'DATE' => $requestDate,             
                        ))
                        ->expects(\Httpful\Mime::JSON)
                        ->timeout(60)
                        ->send();
            }
           
            $status=$response->code;   
            if ($status != 200){               
                    return  json_encode($this->createErrorResponse($this->createStatusResponse($status), $parameters));        
            }else{
                 $jsonResponse=json_decode($response, true);             
                return json_encode($jsonResponse);
            }                    		
	}
        
        /**
	 * This method manages an asynchronous client request call for a web service. 
	 * 
	 * @param string $servicePath Refers to the path of the web service to call
	 * @param string $method GET/POST method
	 * @param array $parameters The form/query parameters to pass
	 * @return string asyncRequestId A string reference to use to retrieve the result from asynchronous client request
	 * 
	 */
	public function asyncRequestService($servicePath,  $method, array $parameters){
		$asyncRequestId = Oauth2ClientUtils::getUniqueId().Oauth2ClientUtils::generateRandomString(6);
             
        $checkResult = $this->checkVersionRequired(); 
        if($checkResult == "")
        {
         	$newThread=new AsyncRequestThread($asyncRequestId, $this, $servicePath, $method, $parameters);
           	$newThread->start();
        }
        else
        {
          	$errorObj = $this->createErrorObj(CalcFusionClient::RESPONSE_400, $checkResult);
           	$result = json_encode($this->createErrorResponse($errorObj, $parameters));  
           	asyncCallbackResult($asyncRequestId, $result);
        }
           
        return $asyncRequestId;
	}
	
	/**
	 * This method serves as the listener of the returned result of asynchronous client request. 
	 * This method is called from AsyncRequestService class once the web service call return.
	 * The result data is stored on resultStorage collection referenced by the provided asyncRequestId.
	 * @param string $asyncRequestId A unique request id to be use as a reference for later retrieval.
	 * @param string $result The result JSON string format from the web service call.
	 */
	public function asyncCallbackResult($asyncRequestId,  $result)
	{
		$this->resultStorage[$asyncRequestId]=$result;
	}
	
	/**
	 * Call this method to check and retrieve, if the asynchronous service request already returned a result. 
	 * @param string $asyncRequestId A unique request id for reference to the resultStorage collection
	 * @return string JSON String format of the result data, null if not yet available
	 */
	public function getAsyncRequestResult($asyncRequestId)
	{           
		$result = $this->resultStorage[$asyncRequestId];
		if($result){
                    unset( $asyncRequestIdList);
                }
		return $result;
	}
        
        /**
	 * @exclude
	 * This method generate standard response for errors.
	 * @param string $statusCode
	 * @return
	 */
	protected function createStatusResponse($statusCode){
            return $this->createErrorObj($statusCode, static::getResponseMsg($statusCode));		
	}
        
	/**
	 * This method generate standard result data for errors.
	 * @param string $errorObj
	 * @return
	 */
	protected function createErrorResponse_1($errorObj){
            return $this->createErrorResponse($errorObj, null);
	}
	
	/**
	 * This method returns library information such as Context, Version and File Name. 
	 * 
	 * @return string Library information in JSON String format
	 * 
	 */	
	public static function getContextInfo(){
            $calcVersionObj = array();
            if ( !defined('CALCFUSION_CONTEXT_NAME') )
            	$calcVersionObj["context"]=CalcFusionClient::CONTEXT;
            else 
            	$calcVersionObj["context"]=CALCFUSION_CONTEXT_NAME.' '.CalcFusionClient::CONTEXT;
            
            $calcVersionObj["version"]=CalcFusionClient::VERSION;
            $calcVersionObj["filename"]=CalcFusionClient::FILENAME;
			
            $returnValue = json_encode($calcVersionObj);
            return $returnValue;
	}
        
	/**
	 * This method generate standard result data for errors.
	 * @param string $errorObj
	 * @param array $parameters
	 * @return
	 */
	protected function createErrorResponse_2($errorObj, $parameters){
            $response = array();
            $response['response']=$errorObj;
            $response['data']=null;
            $response[CalcFusionClient::CONTEXTINFO_PARAM]=$this->getContextInfoParam();
            if($parameters != null){
                $response['cfxlCustom']=@$parameters['cfxlCustom'];
            }
            return $response;
	}
        
	protected  function createErrorObj($errorCode, $errorMessage){
        $errorObj = array();
        $errorObj['status']='FAILED';
        $errorObj['errorCode']=$errorCode;
        $errorObj['errorMessage']=$errorMessage;
		return $errorObj;
    }
        
	public static function getResponseMsg($code){
    	$constantName='RESPONSE_'.$code;
		$errorMessage=static::getConst($constantName);
		if (!$errorMessage) {
			$errorMessage=' No response message for code=$code !';
		}
		return $errorMessage;
	}
        
	protected static function getConst($constantName){
		return defined('static::'.$constantName) ? constant(get_called_class().'::'.$constantName) : false;
	} 
	
	// check the minimum required version and server settings
	function checkVersionRequired()
	{
		$message = "";
		$minimumVersion = "5.3";
		$minimumAsyncVersion = "5.5";
		$version = explode('.', phpversion());
		$major = (int)$version[0];
		$minor = (int)$version[1];
		$major_minor = $major.".".$minor;
		
		$phpInfoMsg = 'PHP version ('.$major_minor.'), CURL Support: '.get_curlSettings().', AsyncDNS enabled: '.get_asyncDNS().'. ';
		
		if (strnatcmp($major_minor, $minimumVersion) >= 0)
		{
			$curlSettings = get_curlSettings();
			$asyncDNS = get_asyncDNS();
			
			if($curlSettings != "enabled")
				$message .= 'CURL support must set to enabled. ';
			
			if (strnatcmp($major_minor, $minimumAsyncVersion) >= 0)
			{
				if($asyncDNS == 'No')
				{
					$message .= 'Only synchronous service calls can be used with the current server settings. ';
					$message .= 'To enable asynchronous service calls, the CURL AsynchDNS setting must be set to Yes.';
				}	
			}
			else 
				$message .= ' Only synchronous service calls can be used with the current server settings.';
		}
		else
			$message = 'PHP version ('.$major_minor.') is below the minimum required version, must be '.$minimumVersion.' or higher';
		
		if($message != "")
			$message = $phpInfoMsg.$message;
		
		return $message;
	}
	
	// get CURL AsynchDNS value settings
	function get_asyncDNS()
	{
		ob_start();
		phpinfo();
		$phpinfo = array('phpinfo' => array());
		if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
			foreach($matches as $match)
				if(strlen($match[1]))
					$phpinfo[$match[1]] = array();
				elseif(isset($match[3]))
				$phpinfo[end(array_keys($phpinfo))][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
				else
					$phpinfo[end(array_keys($phpinfo))][] = $match[2];
	
				return $phpinfo['curl']['AsynchDNS'];
	}
	
	// get CURL support value settings
	function get_curlSettings()
	{
		ob_start();
		phpinfo();
		$phpinfo = array('phpinfo' => array());
		if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
			foreach($matches as $match)
				if(strlen($match[1]))
					$phpinfo[$match[1]] = array();
				elseif(isset($match[3]))
				$phpinfo[end(array_keys($phpinfo))][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
				else
					$phpinfo[end(array_keys($phpinfo))][] = $match[2];
	
				return $phpinfo['curl']['cURL support'];
	}
	
	function getContextInfoParam()
	{
		if ( !defined('CALCFUSION_CONTEXT_NAME') )
			return CalcFusionClient::CONTEXT.' '.CalcFusionClient::VERSION;
		else
			return CALCFUSION_CONTEXT_NAME.' '.CalcFusionClient::CONTEXT.' '.CalcFusionClient::VERSION;
	}
}
