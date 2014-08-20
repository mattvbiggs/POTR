<?php
    require 'database/dbcommon.php';
    
	function redirect_to($new_location) {
		header("Location: " . $new_location);
		exit;
	}
	
	function get_cptcodes() {
		require 'database/dbcommon.php';
	
		$connection = mysqli_connect($host, $dbUser, $dbPass, $dbName);
		if(mysqli_connect_errno()) {
			die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_error() . ")");
		}
		
		$query = "SELECT * FROM tblCode ORDER BY CPTCode";
		$result = mysqli_query($connection, $query);
		
		return $result;
	}
	
	function get_visittypes() {
		require 'database/dbcommon.php';
	
		$connection = mysqli_connect($host, $dbUser, $dbPass, $dbName);
		if(mysqli_connect_errno()) {
			die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_error() . ")");
		}
		
		$query = "SELECT * FROM tblVisitType";
		$result = mysqli_query($connection, $query);
		
		return $result;
	}
	
	function get_insurancetypes() {
		require 'database/dbcommon.php';
		
		$connection = mysqli_connect($host, $dbUser, $dbPass, $dbName);
		if(mysqli_connect_errno()) {
			die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_error() . ")");
		}
		
		$query = "SELECT * FROM tblInsurance";
		$result = mysqli_query($connection, $query);
		
		return $result;
	}
	
	function get_officevisittypes() {
		require 'database/dbcommon.php';
		
		$connection = mysqli_connect($host, $dbUser, $dbPass, $dbName);
		if(mysqli_connect_errno()) {
			die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_error() . ")");
		}
		
		$query = "SELECT * FROM tblOfficeVisitType";
		$result = mysqli_query($connection, $query);
		
		return $result;
	}
	
	function get_newpatientdata() {
		require 'database/dbcommon.php';
		
		$connection = mysqli_connect($host, $dbUser, $dbPass, $dbName);
		if(mysqli_connect_errno()) {
			die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_error() . ")");
		}
		
		$query = "SELECT A.LastName, COUNT(*) ";
		$query .= "FROM tblBillingRecord BR ";
		$query .= "INNER JOIN tblAccount A ON BR.AccountID = A.AccountID ";
		$query .= "WHERE BR.VisitID = 1 ";
		$query .= "GROUP BY A.LastName";
		
		$result = mysqli_query($connection, $query);
		
		return $result;
	}
    
    function get_newpatientreport() {
        require 'database/dbcommon.php';
		
		$connection = mysqli_connect($host, $dbUser, $dbPass, $dbName);
		if(mysqli_connect_errno()) {
			die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_error() . ")");
		}
        
        $query = "SELECT CONCAT(a.FirstName, ' ', a.LastName) AS PhysicianName, MONTHNAME(STR_TO_DATE(br.BillingDate, '%m/%d/%Y')) AS 'Month', COUNT(*) AS 'NumPatients' ";
        $query .= "FROM tblBillingRecord br ";
        $query .= "INNER JOIN tblAccount a ON br.AccountID = a.AccountID ";
        $query .= "WHERE br.VisitID = 1 ";
        $query .= "GROUP BY MONTHNAME(STR_TO_DATE(br.BillingDate, '%m/%d/%Y')), a.Lastname ";
        $query .= "ORDER BY MONTH(STR_TO_DATE(br.BillingDate, '%m/%d/%Y')), a.Lastname";
        
        $result = mysqli_query($connection, $query);
        
        return $result;
    }
    
    function get_billingRecordByID($id) {
        require 'database/dbcommon.php';
        
        $connection = mysqli_connect($host, $dbUser, $dbPass, $dbName);
		if(mysqli_connect_errno()) {
			die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_error() . ")");
		}
        
        $query = "SELECT * FROM tblBillingRecord ";
        $query .= "WHERE RecordID = " . $id;
        
        $result = mysqli_query($connection, $query);
        
        return $result;
    }
?>