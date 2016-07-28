<?php

/*
 *Validate class for server side validation
 *Features:
 	*Text pattern matching
	*String length checks
	*Password generator
*/

class Validate
{
	private $type;
	private $regex;
	
	/*
		Determine whether the text in the password field matches that in the retype field
	*/
	
	public function retypePass($pass, $retype)
	{
		if($retype !== $pass){
			return "Password mismatch";
		}else {
			return true;
		}
	}
	
	/*
		Validate the input in the number field(s)
	*/
	
	public function validateNum($num)
	{
		if(!preg_match('/^[0-9]+$/', $num)){
			return "ID mismatch";
		}else {
			return true;
		}
	}
	
	/*
		Option for text validation based on type that is either true or false
		True - characters with spaces
		False - characters without spaces
	*/
	
	public function setType($val = false){
		if(is_bool($val)){
			$this->type = $val;
			switch($this->type){
				case true:
					$this->regex = '/^[a-zA-Z\s]+$/';
					break;
				
				case false:
					$this->regex = '/^[a-zA-Z]+$/';
					break;
			}
		}else {
			return false;
		}
		
		return array(
			'regex' => $this->regex,
			'type' => $this->type
		);
	}
	
	/*
		Perform text validation
	*/
	
	public function validateText($text)
	{
		if(preg_match($this->regex, $text)){
			return true;
		}else {
			return "Text mismatch";
		}
	}
	
	/*
		String length check
	*/
	
	public function strLength($str, $len){
		mb_internal_encoding('UTF-8');
		
		if(mb_strlen($str) <= $len){
			return true;
		}else {
			return "Max length exceeded";
		}
	}
	
	/*
		Generate a password from the specified character field
	*/
	
	public function pwdGenerator($len)
	{
		$char = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_@';
		
		return substr(str_shuffle($char), 0, $len);
	}
	
	/*
		Pattern match for the password
	*/
	
	public function checkPass($pass)
	{
		if(!preg_match('/^[0-9a-zA-Z\@\_]+$/', $pass)){
			return 'Password mismatch';
		}else {
			return true;
		}
	}
}

/*
 *Designed by Lochemem Bruno Michael
*/
