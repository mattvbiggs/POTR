<?php
    session_start();
	
	require_once("common.php");
	    
	if (!isset($_SESSION["LoggedIn"])) {
		redirect_to("login.php");
	}
    
    $firstname = $_SESSION["Firstname"];
	$lastname = $_SESSION["Lastname"];
	$UserName = $firstname . " " . $lastname;
	
	$Location = $_SESSION["Location"];
	
	$login = $_SESSION["Login"];
	$title = $_SESSION["Title"];
	$AccountID = $_SESSION["ID"];
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Edit | POTR</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/jquery-ui.css">
    
    <!-- contains javascript functions -->
	<script src="scripts/functions.js"></script>
	
	<script src="scripts/jquery-1.11.0.min.js"></script>
	<script src="scripts/jquery-ui-1.10.3/ui/jquery-ui.js"></script>
    
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/potr-theme1/jquery-ui-1.10.4.custom.css" />
    
    <script>
        $(document).ready(function() {
            
        });
    </script>
</head>
<body>
s
</body>
</html>
