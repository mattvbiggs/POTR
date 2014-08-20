<?php
    require 'dbcommon.php';
    
    $connection = mysqli_connect($host, $dbUser, $dbPass, $dbName);
	if(mysqli_connect_errno()) {
		die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_error() . ")");
	}
    
    $cptcode = $_POST["cptcode"];
    
    $query = "SELECT * FROM tblCode WHERE CPTCode = '%{$cptcode}%'";
	$result = mysqli_query($connection, $query);
        
    $check = mysqli_num_rows($result);
    if($check == 0)
    {
        $return = "The CPT Code you entered could not be found in the system. Please verify you have entered it correctly.";
    }
    
    echo $return;
?>
