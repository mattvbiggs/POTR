function displayReferral(specialty, group, referralID, referralName) {
	$("#referring-specialty").show();
	$("#referring-group").show();
	$("#show-doctor").show();
	$("#lastname-search").hide();
	
	$("#show-specialty").html(specialty);
	$("#show-group").html(group);
	$("#doctor-name").html(referralName);
	$("#hdnReferralID").val(referralID);
	
	$("#referral-results").hide();
}

function clearReferralInfo() {
    $("#referring-specialty").hide();
    $("#referring-group").hide();
    $("#show-doctor").hide();
    $("#lastname-search").show();

    $("#show-specialty").html("");
    $("#show-group").html("");
    $("#doctor-name").html("");
    $("#hdnReferralID").val("");

    $("#referral-results").html("");
    $("#referral-results").hide();
}

function resetSearch() {
    $("#cpt-code-list").html("");
    $("#search-msg").text("");

    $("#txtCptCode").val("");
    $("#txtDesc").val("");
}

function resetEditCPTCode() {
    /* the following line will reset all inputs within this div */
    $('div[id=manage-cpt-codes] input[type=text]').val("");
    $("#update-desc").val("");

    $("#edit-search-msg").text("");
    $("#edit-cpt-code").hide();
}

function resetAddCase() {
    /* the following line will reset all inputs within this div */
    $('div[id=form-add-case] input[type=text]').not('#servicedate').val("");

    $("#enter-referring").hide();
    $("#referring-specialty").hide();
    $("#referring-group").hide();
    $("#visittypes").val('0');
    $("#officevisittype").val('0');
    $("#officevisittypes").hide();
    $("#insurancetype").val('0');
    $("#select-insurance").hide();
       
}

function hideDivs() {
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

    /* Hide site tools divs */
    $("#manage-user").hide();
    $("#manage-cpt-codes").hide();
    $("#edit-cpt-code").hide();
}

function validateData() {
	var inputvalid = true;
	
	if ($("#servicedate").val() == "") {
		showError("Service Date Required.");
		return false;
	}

	if ($("#cptcode").val() == "") {
	    showError("CPT Code is required.");
	    return false;
	}
		
	if ($("#case-id").val() == "") {
		showError("Case Identifier Required.");
		return false;
	}
	
	if ($("#visittypes").val() == '0') {
		showError("Please select a Visit Type.");
		return false;
	}
	
	$("#message-div").hide();
	return inputvalid;
}

function showError(message) {
	$("#message-div").show();
	$("#message-div").text(message);
	$("#message-div").addClass("error-text");
}

function appendNewCPTCode(codeID, Code, codeDesc, codeRVU)
{
	var table = "<tr>";	
	table += "<td align='center' valign='top'><a id='" + codeID + "-" + Code + "' href='#'>" + Code + "</a></td>";
	table += "<td width='80%'>" + codeDesc + "</td>";
	table += "<td align='center'>" + codeRVU + "</td>";
	table += "</tr>";
	
	return table;
}

/* These functions were used for the reports */
function generateexcel(tableid) {
	 var table= document.getElementById(tableid);
	 var html = table.outerHTML;
	 var data_type = 'data:application/vnd.ms-excel';
	 var a = document.createElement('a');
	 
	 a.href = data_type + ', ' + encodeURIComponent(html);
	 a.download = 'new_patient_report.xls';
	 a.click();
	 //window.open('data:application/vnd.ms-excel,' + encodeURIComponent(html));
}

function LoadTempBarChart() {
	var s1 = [6, 6, 9];
	var s2 = [6, 8, 5];
	var s3 = [2, 2, 2];
	var s4 = [1, 0, 0];
	var s5 = [2, 2, 2];
	// Can specify a custom tick Array.
	// Ticks should match up one for each y value (category) in the series.
	var ticks = ['March', 'April', 'May'];

	var plot1 = $.jqplot('chartdiv', [s1, s2, s3, s4, s5], {
		// The "seriesDefaults" option is an options object that will
		// be applied to all series in the chart.
		seriesDefaults:{
			renderer:$.jqplot.BarRenderer,
			rendererOptions: {fillToZero: true}
		},
		// Custom labels for the series are specified with the "label"
		// option on the series option.  Here a series option object
		// is specified for each series.
		series:[
			{label:'Lyell'},
			{label:'Hocker'},
			{label:'Crase'},
			{label:'Johnson'},
			{label:'Eichenberger'}
		],
		// Show the legend and put it outside the grid, but inside the
		// plot container, shrinking the grid to accomodate the legend.
		// A value of "outside" would not shrink the grid and allow
		// the legend to overflow the container.
		legend: {
			show: true,
			location: 'ne',
			placement: 'outside'
		},
		title: {
			text: 'Dr. Berg Surgeon Referrals',
			show: true,
		},
		axesDefaults: {
			tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
			tickOptions: {					  
			  fontSize: '12pt'
			}
		},
		axes: {
			// Use a category axis on the x axis and use our custom ticks.
			xaxis: {
				renderer: $.jqplot.CategoryAxisRenderer,
				ticks: ticks
			},
			// Pad the y axis just a little so bars can get close to, but
			// not touch, the grid boundaries.  1.2 is the default padding.
			yaxis: {
				pad: 1.05,
				tickOptions: {formatString: '%d'},
				label: 'Patients Referred'
			}
		}
	});
}