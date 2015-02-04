<?php

/**
 * The {@code IUser} interface provides methods to reference client user information.
 * 
 * @version 1.0
 * @since 2014-06-30
 *
 */
interface IUser {

	/**
	 * A getter method for the user name property
	 * 
	 * @return The String value of the Name of the user
	 * 
	 */
	public function  getName();

	/**
	 * A getter method for the userId property 
	 * 
	 * @return The String value of the User ID of the user
	 * 
	 */
	public function  getUserId();

	//String getUsgId();

	//String getContactId();

	/**
	 * A getter method for the contactName property
	 * 
	 * @return The String value of the Contact Name of the user
	 * 
	 */
	public function  getContactName();

	/**
	 * A getter method for the accountId property
	 * 
	 * @return The String value of the Account ID of the user
	 * 
	 */	
	public function  getAccountId();

	/**
	 * A getter method for the loginKey property
	 * 
	 * @return The String value of the Login Key of the user
	 * 
	 */	
	public function  getLoginKey();
	
}
