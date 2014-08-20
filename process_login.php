<?php

	require_once("common.php");
	
	if (isset($_POST['login']) && isset($_POST['password'])) {
		$login = $_POST['login'];
		$pass = $_POST['password'];
		
		$connection = mysqli_connect($host, $dbUser, $dbPass, $dbName);
		if(mysqli_connect_errno()) {
			die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_error() . ")");
		}
		
		$query = "SELECT A.*, L.Name FROM tblAccount A, tblLocation L ".
				"WHERE A.LocationID = L.LocationID AND Login = '{$login}' AND Password = '{$pass}'";
		$result = mysqli_query($connection, $query);
		
		$check = mysqli_num_rows($result);
		if ($check == 0) {
			echo "Invalid Login!";
			redirect_to("login.php?error=yes");
		}
		
		if(!$result) {
			die("Something went horribly wrong!!");
		} else {
			while($row = mysqli_fetch_assoc($result)) {
				if (!$row) {
					$message = "Login Failed: Incorrect username or password.";
					redirect_to("login.php?error=yes");
				} else {
					session_start();
					$_SESSION["ID"] = $row["AccountID"];
					$_SESSION["Login"] = $row["Login"];
					$_SESSION["Firstname"] = $row["FirstName"];
					$_SESSION["Lastname"] = $row["LastName"];
					$_SESSION["Title"] = $row["Title"];
					$_SESSION["Location"] = $row["Name"];
					$_SESSION["LoggedIn"] = "True";
					redirect_to("home.php");
				}
			}
		}
	} 
?>