<?php
	$host = "localhost";
	$dbUser = "vleaminc_potr";
	$dbPass = "Potr@dm!n01";
	$dbName = "vleaminc_potr";
	
	$cptcode = $_POST["cptcode"];
	$cptdesc = $_POST["cptdesc"];
	$wrvu = $_POST["wrvu"];
	
	$connection = mysqli_connect($host, $dbUser, $dbPass, $dbName);
	if(mysqli_connect_errno()) {
		die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_error() . ")");
	}
	
	$query = "INSERT INTO tblCode(CPTCode, Description, wRVU) ";
	$query .= "VALUES('$cptcode','$cptdesc','$wrvu')";
	
	$result = mysqli_query($connection, $query);
	
	if($result) {
		$result = mysqli_query($connection, "SELECT LAST_INSERT_ID()");
		$id = mysqli_fetch_row($result);
		mysqli_close($connection);
		echo $id[0];
	}
?>