<table id="cpt-list" width="780px" class="pretty-table">
	<tbody>
		<tr>
			<th width="75%" colspan="2">Physician Fee Schedule</th>
			<th align="center">Work RVU</th>
		</tr>
		<div id="cpt-codes-list">
		<?php 
			$result = get_cptcodes();
			while($row = mysqli_fetch_assoc($result)) {
				if ($row) {
					$tablerow = "<tr>";
					$tablerow .= "<td align='center' valign='top'><a id='{$row['CodeID']}-{$row['CPTCode']}' href='#'>".$row['CPTCode'] . "</a></td>";
					$tablerow .= "<td width='80%'>".$row['Description']."</td>";
					$tablerow .= "<td align='center'>".$row['wRVU'] . "</td>";
					$tablerow .= "</tr>";
					echo $tablerow;
				}
			}
		?>
		</div>
	</tbody>
</table>