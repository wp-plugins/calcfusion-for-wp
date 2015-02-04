<?php

/**
 * The {@code IAuthorizedClientApp} interface provides read-only methods to reference
 * the authorized user, its clientId and a callbackURl.  
 * 
 * @version 1.0
 * @since 2014-09-13
 */
interface IAuthorizedClientApp {
   /**
	 * A getter method for the clientId
	 * 
	 * @return The String value of the Client ID of the authorized user
	 * 
	 */
	public function  getClientId();
	
	/**
	 * A getter method for the callbackUrl
	 * 
	 * @return The String value of the URL path to call after authentication
	 * 
	 */	
	public function  getCallbackUrl();	
	
	/**
	 * A getter method for authorizedUser
	 * 
	 * @return The detailed information of the authorized user
	 * 
	 */	
	public function  getAuthorizedUser();
}
