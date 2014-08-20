<?php
	require 'dbcommon.php';
	
    $codeid = $_POST["codeid"];
	$cptcode = $_POST["cptcode"];
	$cptdesc = $_POST["cptdesc"];
	$wrvu = $_POST["cptrvu"];
	
	$connection = mysqli_connect($host, $dbUser, $dbPass, $dbName);
	if(mysqli_connect_errno()) {
		die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_error() . ")");
	}
	
    $query = "UPDATE tblCode ";
    $query .= "SET CPTCode = '" . $cptcode . "', Description = '" . $cptdesc . "', wRVU = " . $wrvu;
    $query .= " WHERE CodeID = " . $codeid;
        
	$result = mysqli_query($connection, $query);
	
    if($result) {
        echo "Your Changes Have Been Saved!";
    } else {
        echo "Something Went BOOM!";
    }
?>
