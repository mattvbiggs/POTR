<?php
	include("../JSON.php");
	
	$host = "localhost";
	$dbUser = "vleaminc_potr";
	$dbPass = "Potr@dm!n01";
	$dbName = "vleaminc_potr";
	
	$json = new Services_JSON();
	
	$page = $_GET['page'];
	$limit = $_GET['rows'];
	$sidx = $_GET['sidx'];
	$sord = $_GET['sord'];
	
	session_start();
	$ID = $_SESSION["ID"];
	$login = $_SESSION["Login"];

	if (strpos($login, 'admin') !== false)
	{
		$isAdmin = true;
	} else {
		$isAdmin = false;
	}
	
	$connection = mysqli_connect($host, $dbUser, $dbPass, $dbName);
	if(mysqli_connect_errno()) {
		die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_error() . ")");
	}
	
	$query = "SELECT  COUNT(*) AS count FROM tblBillingRecord";
	if ($isAdmin == false)
	{
		$query .= " WHERE AccountID = " . $ID . "";
	}
	
	$result = mysqli_query($connection, $query);
	
	$row = mysqli_fetch_assoc($result);
	$count = $row['count'];
	
	if ($count > 0) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
	}
	
	if ($page > $total_pages) $page=$total_pages;
	
	$start = $limit*$page - $limit;
	if ($start < 0) $start = 0;
	
	$sql = "SELECT br.RecordID, br.BillingDate, CONCAT(a.FirstName, ' ', a.LastName) AS Physician, c.CPTCode, CONCAT(c.CPTCode, ' - ', c.Description) AS CPTCodeDesc, br.Patient AS CaseID, c.wRVU ";
	$sql .= "FROM tblBillingRecord br INNER JOIN tblAccount a, tblCode c WHERE br.AccountID = a.AccountID AND br.CodeID = c.CodeID ";
	
	if ($isAdmin == false)
	{
		$sql .= "AND a.AccountID = " . $ID . " ";
	}
	
	$sql .= "ORDER BY $sidx $sord LIMIT $start, $limit";
	
	$result = mysqli_query($connection, $sql);
	
	$responce = new StdClass;
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	
	$i=0;
	while($row = mysqli_fetch_assoc($result)) {
		$responce->rows[$i]['id']=$row['RecordID'];
		$responce->rows[$i]['cell']=array('RecordID' => $row['RecordID'],'BillingDate' => $row['BillingDate'],'Physician' => $row['Physician'],'CPTCode' => $row['CPTCode'],'CPTCodeDesc' => $row['CPTCodeDesc'],'CaseID' => $row['CaseID'],'wRVU' => $row['wRVU']);
		$i++;
	}
	
	echo $json->encode($responce);
?>