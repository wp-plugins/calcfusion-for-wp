<?php

/**
 * The {@code ClientTokenGenerator} class provides explicit methods to generate a new Client Access Token.
 * 
 * @version 1.0
 * @since 2014-09-13
 *
 */
class ClientTokenGenerator {
       // public function run(){}
	/**
	 * Constructor for {@code ClientTokenGenerator}
	 */
	public function __construct(){
	}
	
         /**
	 * This method generates a new Access Token based on the old Access Token and a DateTime stamp.
	 * 
	 * @param string $oldAccessToken The Access Token retrieved from the last authentication process
	 * @param string $timeStamp The String value of the current DateTime 
	 * @return The String value of the new Access Token generated
	 */
	public static function createNewAccessToken($oldAccessToken, $timeStamp, $appKey){
		
           $newAccessToken = $oldAccessToken.":".hash('sha256', $timeStamp.$appKey).",".static::generateNonce();
           return base64_encode($newAccessToken);
	}
	
	/**
	 * 
	 * @return md5 of current miliseconds
	 */
	protected static function generateNonce(){
            $millis = static::millitime();    
            return md5($millis, true);
	}
	
        /**
	 * 
	 * @return the passed  miliseconds from the last second
	 */
        protected static function  millitime() {
            $microtime = microtime();
            $now=new DateTime();
            $comps = explode(' ', $microtime);
            return $now->getTimestamp()*1000+sprintf('%03d',  $comps[0] * 1000);
          }              
}
