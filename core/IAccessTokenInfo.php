<?php

/**
 * The {@code IAccessTokenInfo} interface provides read-only methods to reference
 * the authorized user information, its clientId and authorization scopes.  
 * 
 * @version 1.0
 * @since 2014-09-13
 *
 */
 interface IAccessTokenInfo{

	/**
	 * A getter method for the user property
	 * 
	 * @return The {@code IUser} properties
	 * 
	 */
	 public function getUser();

	/**
	 * A getter method for the clientId property
	 * 
	 * @return The String value of the Client ID of the authorized user
	 * 
	 */
	 public function getClientId();

	/**
	 * A getter method for the authorizedScopes property
	 * 
	 * @return The Set of authorization scope
	 * 
	 */
	 public function getAuthorizedScopes();
	
}
?>
