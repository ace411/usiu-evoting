<?php 

/*
 *PDO SQL class for secure database connection
 *Provides transaction support
*/

class Connect 
{

	private $username; 
	private $password; 
	private $host; 
	private $dbname;
	private $db;
	private $stmt;
	private static $instance = null;

	private function __construct()
	{      
		$this->username = "campusAwards2016";
		$this->password = "SD4YcpyXamuspXQ2";
		$this->host = "localhost";
		$this->dbname = "campusAwards2016";	
	}

	/*
		Connect to the database
	*/
	
	private function establishConnection()
	{
		$options = array(
		        	PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', 
		        	PDO::ATTR_PERSISTENT => true
	    	);
		try {
			$this->db = new PDO(
				"mysql:host={$this->host};dbname={$this->dbname};charset=utf8", 
				$this->username, 
				$this->password, 
				$options
			); 
		}catch(PDOException $ex){

		    exit("Cannot connect to the database");
		}
	}
	
	/*
		The clone magic method
		Safeguard the class from cloning
	*/
	
	public function __clone()
	{
		throw new Exception('You cannot clone the object.');
	}
	
	/*
		The wakeup magic method
		Helps reestablish database connections after serialization
	*/

	public function __wakeup()
	{

	}
	
	/*
		Initialize the database connection
	*/
	
	public static function connectTo()
	{
		if(self::$instance == null){

			self::$instance = new Connect();
			self::$instance->establishConnection();
		}

		return self::$instance;
	}
	
	/*
		SQL query generation
	*/

	public function sqlQuery($query)
	{
		$this->stmt = $this->db->prepare($query);
	}
	
	/*
		Bind the parameters in the query
	*/

	public function paramBind($param, $value, $type=null)
	{
		if (is_null($type)) {
			switch (true) {
			    case is_int($value):
			        $type = PDO::PARAM_INT;
			        break;
			    case is_bool($value):
			        $type = PDO::PARAM_BOOL;
			        break;
			    case is_null($value):
			        $type = PDO::PARAM_NULL;
			        break;
			    default:
			        $type = PDO::PARAM_STR;
			}
	    }
	    $this->stmt->bindValue($param, $value, $type);
	}
	
	/*
		Execute the query
	*/

	public function executeQuery()
	{
		return $this->stmt->execute();
	}
	
	/*
		Return the results as an associative array
	*/

	public function resultSet()
	{
		$this->stmt->execute();
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/*
		Return a single result as an associative array
	*/

	public function singleResult()
	{
		$this->stmt->execute();
		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	/*
		Return a single row from the database (query specific)
	*/

	public function singleRow()
	{
		$this->stmt->execute();
		return $this->stmt->fetch();
	}
	
	/*
		Start SQL transaction for multiple queries
	*/
	
	public function beginTransaction()
	{    
	    return $this->db->beginTransaction();
	}
	
	/*
		Perform audit on transaction
	*/

	public function verifyTransaction()
	{
		return $this->db->inTransaction();
	}
	
	/*
		Commit transaction changes to the database
	*/

	public function endTransaction()
	{	    
	    return $this->db->commit();
	}
	
	/*
		Rollback changes specified in transaction
	*/

	public function cancelTransaction()
	{	    
	    return $this->db->rollBack();
	}
	
	/*
		Return a count of all the records in the table
	*/

	public function totalRows()
	{
		return $this->stmt->rowCount();
	}

}	