/**
 * MySQL Database Connection Class
 */
class MySQL {
	var $host; 			// MySQL server hostname
	var $dbUser;		// MySQL username
	var $dbPass;		// MySQL user's password
	var $dbName;		// Name of the database to use
	var $dbConn;		// MySQL Resource link identifier
	var $connectError	// Connection error messages
	
	/**
	 * MySQL constructor (with parameters)
	 */
	function MySQL($host, $dbUser, $dbPass, $dbName) {
		$this->host = $host;
		$this->dbUser = $dbUser;
		$this->dbPass = $dbPass;
		$this->dbName = $dbName;
		$this->connectToDb();
	}
	
	/**
	 * MySQL constructor (default)
	 */
	function MySQL() {
		$this->host = 'localhost';
		$this->dbUser = 'biggs';
		$this->dbPass = 'Ep05Empir3';
		$this->dbName = 'biggs_potr';
		$this->connectToDb();
	}
	
	/**
	 * Establishes a connection to the MySQL database
	 */
	function connectToDb() {
		if (!$this->dbConn = @mysqli_connect($this->host, $this->dbUser, $this->dbPass)) {
			trigger_error('Could not connect to server!');
			$this->connectError = true;
		} else if (!@mysqli_select_db($this->dbName, $this->dbConn)) {
			trigger_error('Could not select database!');
			$this->connectError = true;
		}
	}
	
	/**
	 * Check for MySQL errors
	 */
	function isError() {
		if ($this->connectError) {
			return true;
		}
		$error = mysqli_error($this->dbConn);
		if (empty($error)) {
			return false;
		} else {
			return true;
		}
	}
}