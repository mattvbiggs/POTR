<script type="text/javascript">
    $(document).ready(function() {
        $("#new-patients,#surgeon-referrals,#referral-specifics,#billing-report").hide();
        
        $("#new-patients,#surgeon-referrals,#referral-specifics,#billing-report").dialog({
            modal: true,
            autoOpen: false,
            height: 700,
            width: 850,
            show: "fold",
            hide: {
                effect: "fade",
                duration: 500
            },
            buttons: {
                "Close": function() {
                    $(this).dialog("close");
                }
            }
        });
        
        $("#new-patient-rpt").click(function() {
            $("#new-patients").dialog("open");
        });
        
        $("#surgeon-referral-rpt").click(function() {
            $("#surgeon-referrals").dialog("open");
        });
        
        $("#referral-specific-rpt").click(function() {
            $("#referral-specifics").dialog("open");
        });
        
        $("#billing-rpt").click(function() {
            $("#billing-report").dialog("open");
        });
        
        $("#btnExport").click(function(e) {
            window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#new-patient-report').html()));
            e.preventDefault();
        });
    });
</script>

<p>Below are a list of the available reports. To view a specific report, click on the name of the report and a window should appear. Clicking "Close" or hitting "Esc" should close the window.</p>

<ul id="reports">
    <li><div id="new-patient-rpt"><i class="fa fa-file-text"></i> New Patient Data</div></li>
    <li><div id="surgeon-referral-rpt"><i class="fa fa-file-text"></i> Surgeon Referral Data</div></li>
    <li><div id="referral-specific-rpt"><i class="fa fa-file-text"></i> Referral Specific Data</div></li>
    <li><div id="billing-rpt"><i class="fa fa-file-text"></i> Billing Report</div></li>
</ul>

<div id="new-patients">
    <hgroup>
        <h2 class="h2-report">New Patient Data</h2>
    </hgroup>
    <?php include("reports/newpatients.php"); ?>
</div>

<div id="surgeon-referrals">
    <hgroup>
        <h2 class="h2-report">Surgeon Referral Data</h2>
    </hgroup>
    <?php include("placeholder.html"); ?>
</div>

<div id="referral-specifics">
    <hgroup>
        <h2 class="h2-report">Referral Specific Data</h2>
    </hgroup>
    <?php include("placeholder.html"); ?>
</div>

<div id="billing-report">
    <hgroup>
        <h2 class="h2-report">Billing Report</h2>
    </hgroup>
    <?php include("placeholder.html"); ?>
</div>