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

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Home|RVU Calc</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/jquery.dataTables.css"/>
	
	<!-- contains javascript functions -->
	<script src="scripts/functions.js"></script>
	
	<script src="scripts/jquery-ui-1.10.3/jquery-1.9.1.js"></script>
	<script src="scripts/jquery-ui-1.10.3/ui/jquery-ui.js"></script>
    <script src="scripts/jquery.dataTables.min.js"></script>
	<script src="scripts/tcal.js"></script>
		
	<!-- jqGrid style and script -->
	<link rel="stylesheet" href="css/ui.jqgrid.css">
	<script src="scripts/grid.locale-en.js" type="text/javascript"></script>
	<script src="scripts/jquery.jqGrid.min.js" type="text/javascript"></script>
	
	<!-- <link rel="stylesheet" href="css/font-awesome.min.css"> -->
	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
	<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" href="css/tcal.css">
	
	<!-- jquery custom theme -->
	<link rel="stylesheet" type="text/css" href="css/cupertino/jquery-ui-1.10.3.custom.css" />
		
	<script>
        $(document).ready(function() {
            $("#tabs").tabs();

            /* Hide "add new case" Divs! */
            $("#enter-referring").hide();
            $("#select-insurance").hide();
            $("#referring-specialty").hide();
            $("#referring-group").hide();
            $("#form-add-case").hide();
            $("#message-div").hide();
            $("#officevisittypes").hide();
            $("#new-cpt-code").hide();
            $("#show-doctor").hide();

            /* Hide report divs */
            $("#new-patient-report").hide();
            $("#export-np-button").hide();
            $("#surgeon-referrals").hide();
            $("#referral-data").hide();

            $("#manage-user").hide();
            $("#manage-cpt-codes").hide();

            $("#grid").dataTable();
            
            $("#cpt-search-dialog").dialog({
                modal: true,
                autoOpen: false,
                height: 500,
                width: 750,
                buttons: {
                    Cancel: function() {
                        resetSearch();                        
                        $(this).dialog("close");
                    }
                }
            });
            
            $("#btnSearch").click(function () {
                
                $cptcode = $("#txtCptCode").val();
                $desc = $("#txtDesc").val();
                
                if($cptcode == "" && $desc == "") {
                    $("#search-msg").text("Please enter search criteria.");
                    $("#search-msg").addClass("error-text");
                    return false;
                } else {
                    $("#search-msg").text("");
                }
                
                $.ajax({
                    type: "post",
					url: "database/searchcpt.php",
                    dataType: "text",
					data: "cptcode=" + $cptcode + "&desc=" + $desc,
                    success: function(data) {
                        if(data != "") {
                            $("#cpt-code-list").html(data);
                        } else {
                            $("#search-msg").text("No results found.");
                            $("#search-msg").addClass("error-text");
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
						alert(textStatus + ": " + errorThrown);
					}
                });
            });

            $("label[for='logout']").click(function() {
                location.href="logout.php";
            });

            $("label[for='add-case']").click(function() {
                $("#add-case").hide();
                $("#form-add-case").show("slow");
            });

            $("#cancel").click(function() {
                /* the following line will reset all inputs within this div */
                $('div[id=form-add-case] input[type=text]').val("");

                $("#cancel").val("Cancel");

                $("#add-case").show();
                $("#form-add-case").hide();
                $("#enter-referring").hide();
                $("#referring-specialty").hide();
                $("#referring-group").hide();
                $("#visittypes").val('0');
                $("#message-div").hide();
                $("#officevisittypes").hide();
                $("#select-insurance").hide();
            });

            $("#submit-case").click(function() {
                if (validateData() == true) {
                    $accountid = <?php echo $_SESSION["ID"] ?>;
				    $cptcode = $("#cptcode").val();
					
				    $servicedate = $("#servicedate").val();
					
				    $caseident = $("#case-id").val();
				    $visitid = $("#visittypes").val();
				    $referralid = $("#hdnReferralID").val();
					
				    $insurance = $("#insurancetype").val();
					
				    $.ajax({
					    type: "post",
					    url: "database/submitcase.php",
                        dataType: "json",
					    data: "accountid=" + $accountid + "&cptcode=" + $cptcode + "&servicedate=" + $servicedate + "&caseident=" + $caseident + "&visitid=" + $visitid + "&referralid=" + $referralid + "&insuranceid=" + $insurance,
					    success: function(data) {
                            $status = data.status;
                            $message = data.message;
                            
						    $("#message-div").show();
						    $("#message-div").text($message);
                            
                            if($status == "ERROR") {
                                $("#message-div").removeClass("happy-text");
                                $("#message-div").addClass("error-text");
                            } else {
							    $("#message-div").removeClass("error-text");
                                $("#message-div").addClass("happy-text");
							    $("#cancel").val("Done");
                                
                                var oTable = $("#grid").dataTable();
                                oTable.fnDraw(false);
                            }							
					    }
						
				    });
			    }
		    });
			
			$("#search_cpt").click(function() {
				$("#cpt-search-dialog").dialog("open");
			});
			
			$('#visittypes').change(function() {
				if ($(this).val() == '1') {
					$("#officevisittypes").show();
				} else {
					$("#officevisittypes").hide();
				}
			});
			
			$('#officevisittype').change(function() {
				if ($(this).val() == '1') {
					$("#enter-referring").show();
					$("#select-insurance").show();
				} else {
					$("#enter-referring").hide();
					$("#select-insurance").hide();
				}
			});
			
			$("#check_referral").click(function() {
				var lastname = $("#referral-lname").val();
				
				$.ajax({
					url: "getreferraldetails.php",
					type: "POST",
					data: "lname=" + lastname,
					dataType: 'html',
					success: function(data) {
						if (data == null) {
							$("#message-div").show();
							$("#message-div").text("Referral Not Found!");
							$("#message-div").addClass("error-text");
						} else {
							
							$("#referral-results").show();
							$("#referral-results").html(data);
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
						alert(errorThrown);
					}
				});
			});
			
			$("#reset_referral").click(function() {
				$("#referral-lname").val("");
			});
			
			$("#cpt-code-list").on("click", "a", function() {
                var strdesc = $(this).closest('tr').children('td:eq(1)').text();
				var strcptcode = this.id;
                resetSearch();
				$("#cpt-search-dialog").dialog("close");
				$("#cptcode").val(strcptcode);
                $("#cptcode").attr("title", strdesc);
			});			
						
			$("#referral-results").on("click", "a", function() {
				var referralid = this.id;
				var referralname = $(this).text();
				var referraldetails = referralid.split('|');
				
				displayReferral(referraldetails[1], referraldetails[2], referraldetails[0], referralname);
			});
			
			$("#change-referral").click(function() {
				$("#show-doctor").hide();
				$("#lastname-search").show();
				$("#referral-lname").val("");
			});
			
			/* Events for clicking on Reports */			
			$("#new-patient").click(function() {
				$("#new-patient-report").toggle('slow');
				$("#export-np-button").toggle();
			});
			
			$("#export-newpatients").click(function(e) {
				generateexcel("new-patient-report");
			});
			
			$("#surgeon-referral").click(function() {
				$("#surgeon-referrals").toggle('slow');
			});
			
			$("#referral-report").click(function() {
				$("#referral-data").toggle('slow');
			});
			
			/* Site Tool Events */
			$("#edit-user-tools").click(function() {
				$("#manage-user").slideToggle('fast');
				$("#manage-cpt-codes").hide();
			});
			
			$("#edit-cpt-tools").click(function() {
				$("#manage-cpt-codes").slideToggle('fast');
				$("#manage-user").hide();
			});
			
			$("#save_user").click(function() {
				var firstname = $("#firstname").val();
				var lastname = $("#lastname").val();
				var title = $("#title").val();
				var location = $("#location").val();
				
				var savepass = false;
				
				if ($("#oldpass").val() != "")
				{
					var newpass = $("#newpass").val();
					var confirmpass = $("#confirmpass").val();
					
					if (newpass != confirmpass)
					{
						$("#pass_error").html("Passwords do not match!");
						return;
					}
				}
			});
		});	
	</script>
</head>
<body>
	<?php include("header.php"); ?>
	
	<div id="cpt-search-dialog" class="popup-format">
		<div class="instructions-sm" style="width: 95% !important">Provide full or partial code and/or description to search for a CPT Code.</div>
		<div>
            <form>
                <table style="margin-top: 10px; margin-left: 10px;">
                    <tr>
                        <td><label for="txtCptCode">CPT Code:</label></td>
                        <td><input type="text" id="txtCptCode" class="style-text-input" /></td>
                    </tr>
                    <tr>
                        <td><label for="txtDesc">Description:</label></td>
                        <td><input type="text" id="txtDesc" class="style-text-input" /></td>
                    </tr>
                </table>
                <button id="btnSearch" type="button" class="newcase">Search</button>
                <div id="search-msg"></div>
            </form>
        </div>
        <div id="cpt-code-list"></div>
	</div>
	
	<div id="tabs">
		<ul>
			<li><a href="#tabs-cases"><i class="icon-plus-sign-alt"></i> Cases</a></li>
			<li><a href="#tabs-reports"><i class="icon-bar-chart"></i> Reports</a></li>
			<li class="tab-right"><a href="#tabs-cptcodes"><i class="icon-cog"></i> Tools</a></li>
		</ul>
		<div id="tabs-cases">
			<div id="add-case"><label for="add-case" class="add-case"><i class="icon-medkit"> Add Case</i></label></div>
			<div id="form-add-case" class="form-add-case">
				<form>
					<table>
						<tr>
							<td>					
								<table id="add-case-table">
									<tr>
										<td align="right"><b>Physician</b></td>
										<td>:</td>
										<td><?php echo $UserName; ?></td>
									</tr>
									<tr>
										<td align="right"><b>Service Date</b></td>
										<td>:</td>
										<td><input type="text" id="servicedate" placeholder="Select a Date" class="tcal" /></td>
									</tr>
									<tr>
										<td align="right"><b>CPT</b></td>
										<td>:</td>
										<td>
                                            <div><input type="text" id="cptcode" placeholder="Enter CPT Code" class="add-case-textbox" title="Manually enter CPT Code or click search button." />
											<button id="search_cpt" type="button" title="Search for CPT Code"><i class="icon-search"></i></button></div>
										</td>
									</tr>
									<tr>
										<td align="right"><b>Case Identifier</b></td>
										<td>:</td>
										<td><input type="text" id="case-id" placeholder="Enter Case Identifier" class="add-case-textbox" /></td>
									</tr>
									<tr>
										<td align="right" colspan="2"><input id="submit-case" type="button" value="Submit Case" class="newcase" /></td>
										<td><input type="button" id="cancel" value="Cancel" class="newcase" /></td>
									</tr>
								</table>
							</td>
							<td width="65px">&nbsp;</td>
							<td valign="top">
								<table id="add-case-table">
									<tr>
										<td align="right" valign="top"><b>Visit Type</b></td>
										<td valign="top">:</td>
										<td>
										<?php
											$result = get_visittypes();
											$dropdown = "<select id='visittypes' class='style-dropdown'>";
											$dropdown .= "\r\n<option value='0'>&nbsp;</option>";
											while($row = mysqli_fetch_assoc($result)) {
												$dropdown .= "\r\n<option value='{$row['VisitID']}'>{$row['VisitType']}</option>";
											}
											$dropdown .= "\r\n</select>";
											echo $dropdown;
										?>
										<div id="officevisittypes" style="display: inline;">
											<?php
												$result = get_officevisittypes();
												$dropdown = "<select id='officevisittype' class='style-dropdown'>";
												$dropdown .= "\r\n<option value='0'>&nbsp;</option>";
												while($row = mysqli_fetch_assoc($result)) {
													$dropdown .= "\r\n<option value='{$row['OfficeVisitID']}'>{$row['OfficeVisitType']}</option>";
												}
												$dropdown .= "\r\n</select>";
												echo $dropdown;
											?>
										</div>
										</td>
									</tr>
									<tr id="select-insurance">
										<td align="right"><b>Insurance Type</b></td>
										<td>:</td>
										<td>
											<?php
												$result = get_insurancetypes();
												$dropdown = "<select id='insurancetype' class='style-dropdown'>";
												$dropdown .= "\r\n<option value='0'>&nbsp;</option>";
												while($row = mysqli_fetch_assoc($result)) {
													$dropdown .= "\r\n<option value='{$row['InsuranceID']}'>{$row['InsuranceType']}</option>";
												}
												$dropdown .= "\r\n</select>";
												echo $dropdown;
											?>
										</td>
									</tr>
									<tr id="enter-referring">
										<td align="right" valign="top"><b>Referring Doctor</b></td>
										<td valign="top">:</td>
										<td valign="top">
                                            <div id="lastname-search">
											    <input type="text" id="referral-lname" placeholder="Search by Last Name" class="referring-name-textbox" />
											    <button id="check_referral" type="button" title="Check Referral"><i class="icon-ok"></i></button>
											    <button id="reset_referral" type="button" title="Reset"><i class="icon-refresh"></i></button><br />
                                                <?php include("referralresults.php"); ?>
                                            </div>                                            
											<div id="show-doctor">
												<div id="doctor-name" style="display: inline;"></div>
												<input id="change-referral" type="button" value="Change" style="display: inline;" />
											</div>
										</td>
									</tr>
									<tr id="referring-specialty">
										<td align="right"><b>Specialty</td>
										<td>:</td>
										<td><label id="show-specialty" for="specialty">Show referrings specialty</label></td>
									</tr>
									<tr id="referring-group">
										<td align="right"><b>Medical Group</td>
										<td>:</td>
										<td><label id="show-group" for="specialty">Show referrings group</label></td>
									</tr>
									<tr>
										<td colspan="3"><input type="hidden" id="hdnReferralID" /></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</form>
                <div id="message-div">Show a message here, like save complete.</div>
			</div>
			<div id="rvugrid" class="gridDiv" >
				<table id="grid">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Billing Date</th>
                            <th>Physician</th>
                            <th>CPT (mouse over to see full description)</th>
                            <th>Case Identifier</th>
                            <th>Work RVU</th>
                        </tr>
                    </thead>
                    <tbody><?php include("database/get_BillingGrid.php"); ?></tbody>
                    <tfoot></tfoot>
                </table>
				                
				<div id="pager2"></div>
			</div>
		</div>
		<div id="tabs-reports">
			<p class="instructions">The reports tab is having some issues that I'm still looking into.</p>					
		</div>
		
		<div id="tabs-cptcodes">
			<?php include("sitetools.php"); ?>
		</div>
		
		<?php include("cptcodes.php"); ?>
	</div>
</body>
</html>