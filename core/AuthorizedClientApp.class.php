<?php
require_once 'IAuthorizedClientApp.php';

/**
 * The {@code AuthorizedClientApp} class implements the {@code IAuthorizedClientApp} 
 * which holds detailed information of authorized {@code ClientUser} and its allowedScopes. 
 * 
 * @version 1.0
 * @since 2014-09-13
 */
class AuthorizedClientApp implements IAuthorizedClientApp {

	private $allowedScopes=array();
	private  $user;
	private  $appKey;	
	
	/**
	 * Constructor for AuthorizedClientApp
	 * 
	 * @param string $user The {@code ClientUser} class
	 * @param string $allowedScopes The Set<String> value of the allowedScopes 
	 */
	public function __construct( ClientUser $user,  $appKey, $allowedScopes) {
            $this->user = $user;
            $this->appKey = $appKey;
            $this->allowedScopes = $allowedScopes;
	}

	/**
	 * Getter for the authorizedUser.
	 * 
	 * @return {@code ClientUser} class
	 */
	
	public function getAuthorizedUser() {
		return $this->user;
	}

	/**
	 * Getter for the clientId
	 * 
	 * @return The value of the authorized user clientId
	 */
	
	public function getClientId() {
		return $this->appKey;// registeredClient.getClientId();
	}

	/**
	 * Getter for the callbackUrl
	 * 
	 * @return The URL path to call after authentication
	 */
	
	public function getCallbackUrl() {
		return "blank";//registeredClient.getCallbackUrl();
	}
        
         /*
         * function used to print this class variables(when automaticlly casting
         * it to string
         */
        public function __toString(){
        
            $output=get_class()."={";
            $output.="&nbsp; &nbsp; &nbsp; appKey=".$this->appKey."<br/>"; 
            $output.="&nbsp; &nbsp; &nbsp; allowedScopes=".print_r($this->allowedScopes, true)."<br/>"; 
            $output.="&nbsp; &nbsp; &nbsp;  user={ ".$this->user." }<br/>"; 
            $output.="&nbsp; &nbsp; }";
            return $output;
        }
}
