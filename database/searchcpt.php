<?php
    require 'dbcommon.php';

    include("../JSON.php");
    
    $cptcode = $_POST["cptcode"];
    $cptdesc = $_POST["desc"];
    $searchtype = $_POST["type"];
    
    $query = "SELECT * FROM tblCode WHERE ";
    
    if (strlen($cptcode) > 0) {
        $where = "CPTCode LIKE '%" . $cptcode . "%' ";   
    }
    
    if ((strlen($where) > 0) && (strlen($cptdesc) > 0)) {
        $where .= " AND ";   
    }
    
    if (strlen($cptdesc) > 0) {
        $where .= "Description LIKE '%" . $cptdesc . "%' ";
    }
    
    $query .= $where;
    $query .= "ORDER BY CPTCode";
    
    $connection = mysqli_connect($host, $dbUser, $dbPass, $dbName);
    if(mysqli_connect_errno()) {
        die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_error() . ")");
    }
            
    $result = mysqli_query($connection, $query);
    $check = mysqli_num_rows($result);
    
    if($searchtype == "edit") {
        $json = new Services_JSON();
        
        if ($check == 0 || $check > 1) {
            echo $json->encode($check);
        } else {
            $row = mysqli_fetch_assoc($result);
            $data = array(
                "CodeID" => $row["CodeID"],
                "CPTCode" => $row["CPTCode"],
                "Desc" => $row["Description"],
                "RVU" => $row["wRVU"]
            );
            echo $json->encode($data);
        }
    } else {
        if($check > 0) {
            $html = "<table id='search-table'>";
            $html .= "<caption>Click on the CPT Code to add it to the case.</caption>";
            $html .= "<thead><tr>";
            $html .= "<th>CPT Code</th><th>Description</th><th>wRVU</th>";
            $html .= "</tr></thead>";
            $html .= "<tbody>";
    
            while($row = mysqli_fetch_assoc($result)) {
		        if ($row) {
			        $tablerow = "<tr>";
			        $tablerow .= "<td align='center' valign='top'><a id='{$row['CPTCode']}' href='#'>".$row['CPTCode'] . "</a></td>";
			        $tablerow .= "<td width='80%'>".$row['Description']."</td>";
			        $tablerow .= "<td align='center'>".$row['wRVU'] . "</td>";
			        $tablerow .= "</tr>";
			
                    $html .= $tablerow;
		        }
	        }
    
            $html .= "</tbody></table>";
        }
    
        echo $html;
    }
    
    mysqli_close($connection);
?>