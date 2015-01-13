<?php

/**
 * The {@code ClientUser} class implements {@code IUser} to provide methods to set/get 
 * client user information properties.
 * 
 * @version 1.0
 * @since 2014-09-13
 *
 */
class ClientUser {
        private  $userId;
	private  $contactName;
	private  $accountId;
	private  $loginKey;
	
	/**
	 * Constructor for {@code ClientUser}
         * 
	 * @param string $userId
	 * @param string $contactName 
     * @param string $accountId 
	 * @param string $loginKey 
	 */
	public function __construct($userId, $contactName, $accountId, $loginKey){
            $this->userId=$userId;
            $this->contactName=$contactName;
            $this->accountId=$accountId;
            $this->loginKey=$loginKey;
	}
	
        /*
         * default constructor for Constructor for {@code ClientUser}
         */
        public static function getBlankObject(){
            return new ClientUser("", "", "", "");
        }          
        
	/**
	 * Getter for the userId property
	 * 
	 * @return The String value of the userId
	 */
	
	public function getUserId() {
		return $this->userId;
	}

	/**
	 * Method to set the value for the userId property
	 * 
	 * @param string $userId The Record ID of the user
	 */
	public function setUserId($userId) {
		$this->userId = $userId;
	}
	
	/**
	 * Getter for the name property
	 * 
	 * @return The String value of the Name of the user
	 */
	
	public function getName() {
            return base64_encode(md5($this->userId));
	}
	


	/**
	 * Getter for the contactName property
	 * 
	 * @return The String value of the Contact Name of the user
	 */
	
	public function getContactName() {
		return $this->contactName;
	}

	/**
	 * Method to set the value for the contactName property
	 * 
	 * @param string $contactName The Contact Name of the user
	 */
	public function setContactName($contactName) {
		$this->contactName = $contactName;
	}

	/**
	 * Getter for the accountId property
	 * 
	 * @return The Account ID of the user
	 */
	
	public function getAccountId() {
		return $this->accountId;
	}

	/**
	 * Method to set the value for the accountId property
	 * 
	 * @param string $accountId The Account ID of the user
	 */
	public function setAccountId($accountId) {
		$this->accountId = $accountId;
	}
	
	/**
	 * Getter for the loginKey property
	 * 
	 * @return The Login Key of the user
	 */
	
	public function getLoginKey() {
		return $this->loginKey;
	}

	/**
	 * Method to set the value for the loginKey property
	 * 
	 * @param string $loginKey The Login Key of the user
	 */
	public function setLoginKey($loginKey) {
		$this->loginKey = $loginKey;
	}
        
        /*
         * function used to print this class variables(when automaticlly casting
         * it to string
         */
        public function __toString(){
            $output=get_class()."={";
            $output.="&nbsp; &nbsp; &nbsp; userId=".$this->userId."<br/>"; 
            $output.="&nbsp; &nbsp; &nbsp;  contactName={ ".$this->contactName." }<br/>"; 
            $output.="&nbsp; &nbsp; &nbsp; accountId=".$this->accountId."<br/>"; 
            $output.="&nbsp; &nbsp; &nbsp;  loginKey={ ".$this->loginKey." }<br/>";
            $output.="&nbsp; &nbsp; }";
            return $output;
        }
}
