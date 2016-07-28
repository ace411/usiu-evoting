<?php 

/*
 *AuthLogin to authenticate users
 *Provides brute force security	
 *Designed by Lochemem Bruno Michael
*/

class AuthLogin
{
	private $db;
	
	function __construct()
	{
		$this->db = Connect::connectTo();
	}
	
	/*
		Create a user
		Applies to the registration page
	*/
	
	public function createUser($id, $pass, $fname, $lname)
	{
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$password = hash('sha512', $pass. $salt);
		for($round = 0; $round < 65536; $round++) 
        { 
            $password = hash('sha512', $password . $salt); 
        }
		
		$this->db->beginTransaction();
		$this->db->sqlQuery("
			SELECT 1
			FROM students 
			WHERE student_id = :id
		");
		$this->db->paramBind(':id', $id);
		$this->db->executeQuery();
		$row = $this->db->singleRow();
		$this->db->sqlQuery("
			INSERT INTO students(student_id, firstname, lastname, password, salt, has_voted)
			VALUES (:id, :fname, :lname, :pass, :salt, :vote)
		");
		$this->db->paramBind(':id', $id);
		$this->db->paramBind(':fname', $fname);
		$this->db->paramBind(':lname', $lname);
		$this->db->paramBind(':pass', $password);
		$this->db->paramBind(':salt', $salt);
		$this->db->paramBind(':vote', 0);
		$this->db->executeQuery();
		if($row){
			$this->db->cancelTransaction();
			return true;
		}else {
			$this->db->endTransaction();
			return false;
		}
	}
	
	/*
		Log the user in
		Works with the login landing page
	*/
	
	public function logUserIn($id, $pass)
	{
		$this->db->sqlQuery("
			SELECT combined_name, password, salt, has_voted, student_id
			FROM students
			WHERE student_id = :user
			AND has_voted = 0
		");
		$this->db->paramBind(':user', $id);
		$this->db->executeQuery();
		$user_row = $this->db->singleResult();
		$loginOK = false;
		$check_lockout = $this->checkLockoutTime();
		$check_locktries = $this->checkLockoutTries();
		if($user_row){
			$check_pass = hash('sha512', $pass.$user_row['salt']);
			for($round = 0; $round < 65536; ++$round){
				$check_pass = hash('sha512', $check_pass.$user_row['salt']);
			}
			if($check_pass === $user_row['password']){
				$loginOK = true;
			}	
		}
		if($loginOK && $check_lockout && $check_locktries){
			unset($user_row['password']);
			unset($user_row['salt']);
			$_SESSION['user'] = $user_row;
			return true;
		}else {
			return false;
		}
	}

	/*
		Log user out
	*/
	
	public function logUserOut()
	{
		if(isset($_SESSION['user'])){
			unset($_SESSION['user']);
		}
	}
	
	/*
		Check to see if user is logged in
	*/

	public function checkUserLogin()
	{
		$check_login = (isset($_SESSION['user'])) && ($_SESSION['user'] == true);
		return $check_login;
	}
	
	/*
		Start the lockout
	*/

	public function startLockout()
	{
		$_SESSION['lock-time'] = date("Y-m-d H:i:s");
		print ("
			<div class='error_mod'>
				You have been locked out of the system for 3 minutes
			</div>
		");
	}
	
	/*
		End the lockout
	*/

	public function endLockout()
	{
		if(!empty($_SESSION['lock-time'])){
			unset($_SESSION['lock-time']);
		}
		if(!empty($_SESSION['lock-tries'])){
			unset($_SESSION['lock-tries']);
		}
	}
	
	/*
		Check to see if the user has tried to login more than the maximum number of attempts
	*/
	
	public function checkLockoutTries()
	{
		$lock_tries = 3;
		if(empty($_SESSION['lock-tries'])){
			$_SESSION['lock-tries'] = 1;
		}else {
			$_SESSION['lock-tries'] += 1;
		}
		if($_SESSION['lock-tries'] > $lock_tries){
			$this->startLockout();
		}else {
			return true;
		}
	}
	
	/*
		Check and set the lockout time
	*/
	
	public function checkLockoutTime()
	{
		$lock_time = 3;
		if(isset($_SESSION['lock-time'])){
			$current_time = new DateTime(date("Y-m-d H:i:s"));
			$wait_time = new DateTime($_SESSION['lock-time']);
			$wait_time->modify('+' . $lock_time . ' minutes');
			if($current_time > $wait_time){
				$this->endLockout();
				return true;
			}else {
				return false;
			}
		}
		return true;
	}
	
	/*
		Redirect user in case login has not been completed
	*/

	public function blankLogin($url)
	{
		header("Location: {$url}");
	}
	
	/*
		Redirect user on logout
	*/

	public function userLogout($url)
	{
		header("Location: {$url}");
	}
	
}

/*
	Start the session and regenerate its id
*/
session_start();
session_regenerate_id();
