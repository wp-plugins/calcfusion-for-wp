<?php
/**
 * Description of Oauth2ClientUtils
 *
 * @author Gozman
 * The {@code Oauth2ClientUtils} class provides utility static functions.
 * 
 * @version 1.0
 * @since 2014-09-13
 *
 */
 
class Oauth2ClientUtils  {

    /**
	 * @return a string formatted date yyyy-MM-dd HH:mm:ss.SSS.
     * of the current date
	 */
    public static  function getFormattedDate() {      
        $now=new DateTime();
        $miliseconds=Oauth2ClientUtils::getNowMilisec();
        $now->setTimezone(new DateTimeZone('GMT'));
        $now=$now->format("Y-m-d H:i:s");
        $now.=".".$miliseconds;
        
        return $now;
    }
    
    public static function createNewAccessToken($oldAccessToken, $timeStamp){
            echo "<h2>Here</h2>";
           $newAccessToken = $oldAccessToken.":".$timeStamp.",".static::generateNonce();
           return base64_encode($newAccessToken);
	}
        
    /*
     * @return the number of miliseconds from the last second 
     */
     protected static function  getNowMilisec() {
         $microtime = microtime();   
         $comps = explode(' ', $microtime);
         $miliseconds=sprintf('%03d',  $comps[0] * 1000);
         return $miliseconds;
     }
          
	/**
	 * @param DateTime $date The date to be formatted
	 * @param string $pattern The pattern of the required string date format 
	 * @return a date formatted  based on the specified format.
	 */
    public static function getFormattedDate2(DateTime $date,  $pattern) {
        
        $string = '';
        if ($date){
            $date->setTimezone(new DateTimeZone('GMT'));
            $string=$date->format($pattern);
        }
        return $string;
    }
    
    /**
     * This method add character on the left side of the string provided 
     * if its length is less than the specified length. 
     * @param string $string The string to modify
     * @param string $length The target string length 
     * @param string $ch The character to add to complete the target length
     * @return
     */
    public static function lpad($string, $length, $ch){
       return str_pad($string,$length,$ch, STR_PAD_LEFT);
    }
    
    /**
     * This method generate random character or string based with the specified length
     * @param string $length The number of random character to be generated
     * @return A random character or string
     */
    public static function generateRandomString($length){

        $buffer='';
        for ($i = 0; $i< $length; $i++) {
                $buffer.=static::getRandonCharacter();
        }
	
        return $buffer;
    }    
    
    const  RANDOM_CHARS = "ambzcndyepfxgqhwirjvkslut";
    /**
     * This method generate random character
     * @return A random character
     */
    public static function getRandonCharacter() {
        $charactersLength = strlen(Oauth2ClientUtils::RANDOM_CHARS);
        $index = rand(0, $charactersLength-1);
        return substr(Oauth2ClientUtils::RANDOM_CHARS,$index,1);
    }
    
    /**
     * This method will generate a unique id which can be use as reference
     *  for each request service call.
     * @return Unique string
     */
    public static function getUniqueId(){
        $returnStr = "";
        $currentDateTime = static::getFormattedDate2(new DateTime(), "YmdHis");
        $currentDateTime.=Oauth2ClientUtils::getNowMilisec();
        
        $returnStr .= substr($currentDateTime, 0, 8).'a';
        $returnStr .= substr($currentDateTime,8, 4).static::getRandonCharacter();
        $returnStr .= substr($currentDateTime,12);

        $secMillis = substr($currentDateTime,12);
        $secMillis = substr($secMillis,4).substr($secMillis,3,1).substr($secMillis,2,1).$secMillis; 

        $randomStr = '';
        for ($i = 0; $i < 4; $i++){
            $randomNum = substr($secMillis, 2*$i, 2);
            $randomNum = (int)$randomNum;
            if($randomNum > 24){
                $counter = floor($randomNum/24);
                $randomNum -= (24 * $counter);
            }
           $randomStr .=  substr(Oauth2ClientUtils::RANDOM_CHARS, $randomNum, 1);
        }

        $returnStr .= $randomStr.static::getRandonCharacter();
       
        return $returnStr;
    }
}
