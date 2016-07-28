<?php

/*
 *CSRF protection class
 	*CSRF - Cross Site Request Forgery
 *Creates a cryptographically secure string to validate entry of details
 *Serves as an alternative to the Google ReCaptcha service	
*/

class CSRF 
{
	
	/*
		Generates the string with the native PHP ssl random pseudo bytes function
	*/
	
	public function generate()
	{
		return $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(64));
	}
	
	/*
		Check the token session for a match
	*/
	
	public function check($token)
    {
    	if(isset($_SESSION['token']) && $token == $_SESSION['token']){
    		unset($_SESSION['token']);
    		return true;
    	}
    	return false;
    }
}

/*
 Designed and developed by Lochemem Bruno Michael
*/
