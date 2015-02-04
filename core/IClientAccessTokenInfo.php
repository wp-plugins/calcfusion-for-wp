<?php
require_once 'IAccessTokenInfo.php';
/**
 * The {@code IClientAccessTokenInfo} interface extends the {@code IAccessTokenInfo} to provide 
 * additional methods for the properties of the generated Access Token.
 * 
 * @version 1.0
 * @since 2014-09-13
 */
interface IClientAccessTokenInfo extends IAccessTokenInfo {
   /**
	 * A getter method for the IAuthorizedClientApp property
	 * 
	 * @return The IAuthorizedClientApp properties
	 * 
	 */
	public function  getClientApp();

	/**
	 * A getter method for the expireIn property
	 * 
	 * @return The Long value of the Expiration Date
	 * 
	 */
	public function   getExpiresIn();

	/**
	 * A getter method for the refreshToken property
	 * 
	 * @return The String value of the Refresh Token
	 * 
	 */
	public function   getRefreshToken();

	/**
	 * A getter method for the accessToken property
	 * 
	 * @return The String value of the Access Token
	 * 
	 */
	public function   getAccessToken();	

	/**
	 * This method processes the update of new Access and Refresh Token values. 
	 * 
	 * @param newAccessToken The String value of the new Access Token
	 * @param newRefreshToken The String value of the new Refresh Token 
	 * 
	 */
	public function   updateTokens($newAccessToken, $newRefreshToken);

	/**
	 * A method to check for the expiration of the access token 
	 * 
	 * @return True if the Expiration Date has already expired
	 * 
	 */
	public function  isExpired();
}
