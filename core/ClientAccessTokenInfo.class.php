<?php
    require_once 'IClientAccessTokenInfo.php';
    
/**
 * The {@code ClientAccessTokenInfo} class implements 
 * {@code IClientAccessTokenInfo} to provide read-only methods
 * for the properties of the generated Access Token.
 * 
 * @version 1.0
 * @since 2014-09-13
 *
 */
class ClientAccessTokenInfo implements IClientAccessTokenInfo {
	
	private  $clientApp;
	private  $expiresIn;
	
	private  $accessToken;
	private  $refreshToken;
	private  $validUntil;
        
	/**
	 * Constructor for {@code ClientAccessTokenInfo}
	 * 
	 * @param string $accessToken The Access Token in String format
	 * @param string $refreshToken The Refresh Token in String format
	 * @param string $clientApp The {@code AuthorizedClientApp} class
	 * @param string $expiresIn The Long value of the Expiration Date(in miliseconds)
	 */
	public function __construct($accessToken, $refreshToken, AuthorizedClientApp $clientApp, $expiresIn) {
		$this->accessToken = $accessToken;
		$this->refreshToken = $refreshToken;
		$this->clientApp = $clientApp;		
                if (is_array($expiresIn)){
                    $expiresIn=reset($expiresIn);
                }
                $this->expiresIn = $expiresIn;
		$now = new DateTime();
		$this->validUntil = $now->getTimestamp()*1000+$expiresIn;
	}

        /*
         * Method used to generate an object(using reflection) from an associative array 
         * by using the class constructor
         * @param array $array The associative array that has keys with the same name as the class fields
		 * @param string $className The classname of the object to be instantiated(if null then defaults to current class)
         * @return The object obtained from the array
         */
        static function getObjFromAssArray(array $array, $className=null){
            if ($className==null){
                $className=get_class();
            }
            $args=array();
            $reflection = new ReflectionClass($className);
            $params = $reflection->getConstructor()->getParameters();
  
            foreach ($params AS $param) {
                $paramName=$param->getName();
                if (!$param->getClass()){    
                    $args[]=$array[$paramName];
                }else{
                    $subArray=$array[$paramName];
                    if (!is_array($subArray)) {
                        throw new Exception(" Bad response for constructing $className");
                    }
                    $args[]=ClientAccessTokenInfo::getObjFromAssArray($subArray, $param->getClass()->getName());
                }
            }
            $objInstance = $reflection->newInstanceArgs($args);
            return $objInstance;
        }
	/**
	 * Getter for the clientApp property
	 * 
	 * @return {@code AuthorizedClientApp} class
	 */
	public function getClientApp() {
            return $this->clientApp;
	}

	/**
	 * Getter for the expireIn property
	 * 
	 * @return The Long value of the Expiration Date
	 */
	
	public function getExpiresIn() {
            return $this->expiresIn.getMillis();
	}

	/**
	 * Getter for the refreshToken property
	 * 
	 * @return The String value of the Refresh Token
	 */
	
	public function getRefreshToken() {
		return $this->refreshToken;
	}

	/**
	 * Getter for the accessToken property
	 * 
	 * @return The String value of the Access Token
	 */
	
	public function getAccessToken() {
		return $this->accessToken;
	}

	/**
	 * The method that manages the update of new Access and Refresh Token values. 
	 * 
	 * @param string $newAccessToken The String value of the new Access Token
	 * @param string $newRefreshToken The String value of the new Refresh Token
	 */
	
	public function updateTokens($newAccessToken,  $newRefreshToken) {
		$this->accessToken = $newAccessToken;
		$this->refreshToken = $newRefreshToken;
	}

	/**
	 * 
	 * @return True if the Expiration Date has already expired
	 */
	
	public function isExpired() {
            return static::getNowInMilisec>validUntil;
	}
	
        /**
	 * 
	 * @return the timestamp transformed into miliseconds
	 */
         protected static function  getNowInMilisec() {
            $microtime = microtime();
            $comps = explode(' ', $microtime);

            // Note: Using a string here to prevent loss of precision
            // in case of "overflow" (PHP converts it to a double)
            $now=new DateTime().getTimestamp()*1000;
            $now+=sprintf('%03d',  $comps[0] * 1000);
            return $now;
          }
          
	/**
	 * Getter for the clientId property
	 * 
	 * @return The String value of the clientId
	 */
	
	public function getClientId() {
		return "";
	}

	/**
	 * Getter for the authorizedScopes property
	 * 
	 * @return The Set<String> details of the Authorization Scope.
	 */
	
	public function getAuthorizedScopes() {
		return null;
	}

	/**
	 * Getter for the user property
	 * 
	 * @return {@code ClientUser} class
	 */
	
	public function getUser() {
            return $this->clientApp->getAuthorizedUser();
	}
        
        /*
         * function used to print this class variables(when automaticlly casting
         * it to string
         */
        public function __toString(){
           $output=get_class()."={";
            $output.="&nbsp; &nbsp; &nbsp;   expiresIn=".$this->expiresIn."<br/>"; 
            $output.="&nbsp; &nbsp; &nbsp;   accessToken=".$this->accessToken."<br/>"; 
            $output.="&nbsp; &nbsp; &nbsp;   refreshToken=".$this->refreshToken."<br/>"; 
            $output.="&nbsp; &nbsp; &nbsp;   validUntil=".$this->validUntil."<br/>"; 
            $output.="&nbsp; &nbsp; &nbsp;   clientApp={ ".$this->clientApp." }<br/>"; 
            $output.="&nbsp; &nbsp;}";
            return $output;
        }

}
