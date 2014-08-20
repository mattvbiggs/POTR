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
	<title>Home | POTR</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/jquery-ui.css">
	
	<!-- contains javascript functions -->
	<script src="scripts/functions.js"></script>
	
	<script src="scripts/jquery-1.11.0.min.js"></script>
	<script src="scripts/jquery-ui-1.10.3/ui/jquery-ui.js"></script>
	<script src="scripts/tcal.js"></script>
		
    <!-- jqChart script -->
    <script src="scripts/jquery.jqChart.min.js" type="text/javascript"></script>
    
	<!-- jqGrid style and script -->
	<link rel="stylesheet" href="css/ui.jqgrid.css">
	<script src="scripts/grid.locale-en.js" type="text/javascript"></script>
	<script src="scripts/jquery.jqGrid.min.js" type="text/javascript"></script>
	
	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" href="css/tcal.css">
	
	<!-- jquery custom theme -->
	<link rel="stylesheet" type="text/css" href="css/potr-theme1/jquery-ui-1.10.4.custom.css" />
		
	<script>
        $(document).ready(function() {
            $("#tabs").tabs();

            /* Function (in functions.js) that initially hides divs */
            hideDivs();

            $("#grid").jqGrid({
                url: 'database/populategrid.php',
                datatype: 'json',
                mtype: "GET",
                emptyrecords: "No Records Found",
                hidegrid: false,
                colNames:['ID','Billing Date','Physician','CPT Code','Case Identifier','Work RVU'],
                colModel:[
                    {name:'RecordID',index:'RecordID',hidden:true},
                    {name:'BillingDate',index:'BillingDate',width:75,align:'center'},
                    {name:'Physician',index:'Physician',width:100},
                    {name:'CPTCodeDesc',index:'CPTCodeDesc',width:600,cellattr: function (rowId, tv, rawObject, cm, rdata) { return 'style="white-space: normal;"' } },
                    {name:'CaseID',index:'CaseID',width:125,editable:true,edittype:'text'},
                    {name:'wRVU',index:'wRVU',width:65,align:'center'}
                    ],
                pager: jQuery('#pager2'),
                rowNum: 20,
                rowList:[20,30,40,50],
                sortname: 'BillingDate',
                viewrecords: true,
                sortorder: 'desc',
                height: 'auto',
                autowidth: true,
                shrinkToFit: true,
                loadtext: 'Loading Billing Records...',
                caption: 'RVU Productivity',
                jsonReader: {
                    root: "rows",
                    repeatitems: true,
                    id: "id",
                    cell: "cell",
                    page: "page",
                    total: "total",
                    records: "records"
                },
                loadError: function(jqXHR, textStatus, errorThrown) {
                    alert("HTTP status code: " + jqXHR.status + "\n" +
                    "textStatus: " + textStatus + "\n" +
                    "errorThrown: " + errorThrown);
                },
                beforeSelectRow: function(rowid) {
                    if ($(this).jqGrid("getGridParam", "selrow") === rowid) {
                        $(this).jqGrid("resetSelection");
                    } else {
                        return true;
                    }
                }
            });

            $("#grid").navGrid('#pager2', { search: true, edit: false, add: false, del: false }, {}, {}, {}, { closeOnEscape: true, multipleSearch: true, closeAfterSearch: true});
            $("#grid").jqGrid("resetSelection");
                        
            /* If the user logged in is an admin, we want to hide the Case Identifier column */
            var login = "<?php echo $_SESSION["Login"] ?>";
            if(login.indexOf("admin") > 0)
            {
                $("#grid").hideCol("CaseID");
            }
            
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
            
            /* This searches for a CPT code to add to a new case */
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
					data: "cptcode=" + $cptcode + "&desc=" + $desc + "&type=addcase",
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

            /* Cancel Add Case */
            $("#cancel").click(function() {
                $("#cancel").val("Cancel");

                $("#add-case").show();
                $("#form-add-case").hide();
                $("#message-div").hide();
                $("#servicedate").val("");
                
                resetAddCase();
                clearReferralInfo();
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
                                
                                $("#grid").trigger("reloadGrid");
                                resetAddCase();
                                clearReferralInfo();
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
						if (data == "") {
							$("#message-div").show();
							$("#message-div").text("No Referrals Found!");
							$("#message-div").addClass("error-text");
						} else {
							$("#message-div").hide();
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
			
			/* Site Tool Events */
			$("#edit-user-tools").click(function() {
				$("#manage-user").slideToggle('fast', function() {
                    if(!$(this).is(":hidden")) {
                        resetEditCPTCode();
                    }
                });
				$("#manage-cpt-codes").hide();
			});
			
			$("#edit-cpt-tools").click(function() {
				$("#manage-cpt-codes").slideToggle('fast', function() { 
                    if($(this).is(":hidden")) {
                        resetEditCPTCode();
                    }
                });
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
            
            $("#find-cpt-code").click(function() {
                var $cptcode = $("#edit-cpt-search").val();
                                
                if($cptcode == "") {
                    $("#edit-search-msg").text("Please enter search criteria.");
                    $("#edit-search-msg").addClass("error-text");
                    return false;
                } else {
                    $("#search-msg").text("");
                }
                
                $.ajax({
                    type: "post",
					url: "database/searchcpt.php",
                    dataType: "json",
					data: "cptcode=" + $cptcode + "&type=edit",
                    success: function(data) {
                        if(data == 0) {
                            $("#edit-search-msg").text("CPT Code Not Found!");
                            $("#edit-search-msg").addClass("error-text");
                        } else if(data > 1) {
                            $("#edit-search-msg").text("Too Many Results, please enter a specific CPT Code. If you were expecting one result, then there are duplicate CPT Codes and that's a problem.");
                            $("#edit-search-msg").addClass("error-text");
                        } else {
                            $("#cpt-code-id").val(data['CodeID']);
                            $("#update-code").val(data['CPTCode']);
                            $("#update-desc").val(data['Desc']);
                            $("#update-rvu").val(data['RVU']);
                            
                            $("#edit-search-msg").text("");
                            $("#edit-cpt-code").show();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
						alert(textStatus + ": " + errorThrown);
					}
                });
            });
            
            $("#submit-update").click(function() {
                $codeid = $("#cpt-code-id").val();
                $cptcode = $("#update-code").val();
                $cptdesc = $("#update-desc").val();
                $cptrvu = $("#update-rvu").val();
                
                $data = "codeid=" + $codeid + "&cptcode=" + $cptcode + "&cptdesc=" + $cptdesc + "&cptrvu=" + $cptrvu;
                
                $.ajax({
                    type: "post",
                    url: "database/updatecptcode.php",
                    dataType: "text",
                    data: $data,
                    success: function(result) {
                        alert(result);
                        resetEditCPTCode();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
						alert(textStatus + ": " + errorThrown);
					}
                });
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
			<li><a href="#tabs-cases"><i class="fa fa-plus-square"></i> Cases</a></li>
			<li><a href="#tabs-reports"><i class="fa fa-bar-chart-o"></i> Reports</a></li>
			<li class="tab-right"><a href="#tabs-cptcodes"><i class="fa fa-cogs"></i> Tools</a></li>
		</ul>
		<div id="tabs-cases">
			<div id="add-case"><label for="add-case" class="add-case"><i class="fa fa-medkit"> Add Case</i></label></div>
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
											<button id="search_cpt" type="button" title="Search for CPT Code" class="newcase"><i class="fa fa-search"></i></button></div>
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
											    <button id="check_referral" type="button" title="Check Referral" class="newcase"><i class="fa fa-check"></i></button>
											    <button id="reset_referral" type="button" title="Reset" class="newcase"><i class="fa fa-refresh"></i></button><br />
                                                <?php include("referralresults.php"); ?>
                                            </div>                                            
											<div id="show-doctor">
												<div id="doctor-name" style="display: inline;"></div>
												<input id="change-referral" type="button" value="Change" class="newcase" style="display: inline;" />
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
				<table id="grid"></table>
				
				<div id="pager2" class="ui-jqgrid-titlebar"></div>
			</div>
		</div>
		<div id="tabs-reports">
			 <?php include("reports.php"); ?>
		</div>
		
		<div id="tabs-cptcodes">
			<?php include("sitetools.php"); ?>
		</div>
		
		<?php include("cptcodes.php"); ?>
	</div>
    
    <footer id="footer">
		Copyright (c) 2014 - Made By Biggs - v0.5
	</footer>
</body>
</html>