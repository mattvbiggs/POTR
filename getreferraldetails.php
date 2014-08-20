<?php
	require 'database/dbcommon.php';
	
	$connection = mysqli_connect($host, $dbUser, $dbPass, $dbName);
	if(mysqli_connect_errno()) {
		die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_error() . ")");
	}
	
	$lastname = $_POST["lname"];
	
	$query = "SELECT * FROM tblReferral WHERE PhysicianLastName LIKE '%{$lastname}%'";
	$result = mysqli_query($connection, $query);
	$check = mysqli_num_rows($result);
    
    if($check > 0) {
        $html = "<table id='referral-search'>";
        $html .= "<caption>Click on the name to select a referral.</caption>";
        $html .= "<tbody>";
		
	    while($row = mysqli_fetch_assoc($result)) {
		    $html .= "<tr><td>";
		    $referralid = $row['ReferralID']."|".$row['Specialty']."|".$row['MedicalGroup'];
		    $html .= "<a id='".$referralid."' href='#'>".$row['PhysicianFirstName']." ".$row['PhysicianLastName']."</a>";
		    $html .= "</td></tr>";
	    }
	
	    $html .= "</tbody></table>";
    }    
    
    echo $html;
?>