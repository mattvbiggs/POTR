<div>
	<table id="new-patients-tbl" width="780px" class="new-patients-report">
        <legend>Report only shows Office Visits</legend>
        <thead>
		    <tr>
			    <th width="20%">Month</th>
			    <th width="45%">Physician</th>
			    <th width="20%" align="center">Num Patients</th>
			    <th></th>
		    </tr>
        </thead>
        <tbody>
	<?php
		$total = 0;
		$currentmonth = "";

		$result = get_newpatientreport();
		        
        while($row = mysqli_fetch_assoc($result)) {
            $total += $row['NumPatients'];
        }
		
        mysqli_data_seek($result, 0);
        				
		while($row = mysqli_fetch_assoc($result)) {
			if ($row) {		
				
				if ($currentmonth != "" && $currentmonth != $row["Month"]) {
					$tablerow = "<tr class='new-patients-divider'><td colspan='4'></td></tr>";
					$tablerow .= "<tr>";
				} else {
					$tablerow = "<tr>";
				}
				
				if ($currentmonth != $row["Month"]) {
					$tablerow .= "<td width='25%'><b>".$row["Month"]."</b></td>";
				} else {
					$tablerow .= "<td class='clear-td-bg'>&nbsp;</td>";
				}
				
				$currentmonth = $row["Month"];
			
				$tablerow .= "<td width='45%'>".$row["PhysicianName"]."</td>";
				$tablerow .= "<td width='10%' align='center'>".$row["NumPatients"]."</td>";
				
				$percent = ($row["NumPatients"]/$total)*100;
				
				$tablerow .= "<td align='right'>". round($percent, 2) ."%</td></tr>";
				echo $tablerow;
			}
		}
		
		$tablerow = "<tr class='total-row'>";
		$tablerow .= "<td>&nbsp;</td>";
		$tablerow .= "<td>Total</td>";
		$tablerow .= "<td align='center'>".$total."</td>";
		$tablerow .= "<td>&nbsp;</td></tr>";
		echo $tablerow;
	?>
    </tbody>
	</table>
</div>