<?php
	require 'dbcommon.php';
    
    include("../JSON.php");

	$accountid = $_POST["accountid"];
	$cptcode = $_POST["cptcode"];
	$servicedate = $_POST["servicedate"];
	$caseident = $_POST["caseident"];
	$visitid = $_POST["visitid"];
	$referralid = $_POST["referralid"];
	$insuranceid = $_POST["insuranceid"];
	
    $save = true;
    $status = "ERROR";
    $message = "Unknown Error";
    $json = new Services_JSON();
    
	$connection = mysqli_connect($host, $dbUser, $dbPass, $dbName);
	if(mysqli_connect_errno()) {
		die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_error() . ")");
	}
	
    $query = "SELECT CodeID FROM tblCode WHERE CPTCode = '$cptcode'";
    $result = mysqli_query($connection, $query);
        
    $check = mysqli_num_rows($result);
    if($check == 0) {
        $status = "ERROR";
        $message = "The CPT Code you entered cannot be found in the system. Please verify you have entered it correctly.";
        $save = false;
    } else {
        $row = mysqli_fetch_assoc($result);
        $cptcode = $row['CodeID']; 
    }
    
    if ($insuranceid == 0) {
        $insuranceid = 4;
    }
    
    if($save == true) {
        if ($referralid == "") {
		    $query = "INSERT INTO tblBillingRecord(CodeID, VisitID, InsuranceID, AccountID, Patient, BillingDate) ";
		    $query .= "VALUES('$cptcode','$visitid','$insuranceid','$accountid','$caseident','$servicedate')";
	    } else {
		    $query = "INSERT INTO tblBillingRecord(ReferralID, CodeID, VisitID, InsuranceID, AccountID, Patient, BillingDate) ";
		    $query .= "VALUES('$referralid','$cptcode','$visitid','$insuranceid','$accountid','$caseident','$servicedate')";
	    }
	
	    $result = mysqli_query($connection, $query);
	
	    if($result) {
            $status = "SAVED";
		    $message = "Case Saved!";
	    } else {
            $status = "ERROR";
		    $message = "Error saving case! : " . mysqli_error($connection) . " :: QUERY: " . $query;
	    }
    }
    
    echo $json->encode(array("status" => $status, "message" => $message));
	
    mysqli_close($connection);
?>