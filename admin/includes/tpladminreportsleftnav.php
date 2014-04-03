<script language="JavaScript">
			$(document).ready(function()
			{
				document.getElementById( "leftnavcont" ).style.height = (screen.availHeight - 280) + "px";
				
				$( "#leftnav" ).accordion({
					fillSpace: true
				});
				
				
				/*$("div#leftnavcat1, div#leftnavcat2, div#leftnavcat3, div#leftnavcat4").hide(); */
				
				//which form module will be loaded if a module from menu was clicked?
				$("#agentloginlogoutreport").click(function()
				{$("#formcontainer").load("forms/frmagentloginlogout.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #agentloginlogoutreportdesc");
				}
				);
                                $("#agentstatusdetailreport").click(function()
				{$("#formcontainer").load("forms/frmagentstatusdetail.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #agentloginlogoutreportdesc");
				}
				);
				$("#agentperformanceinboundreport").click(function()
				{
					$("#formcontainer").load("forms/frmagentperformanceinbound.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #agentperformanceinboundreportdesc");
				}
				);
				$("#agentwrapupreport").click(function()
				{
					$("#formcontainer").load("forms/frmagentwrapup.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #agentwrapupreportdesc");
				}
				);
				$("#agentwrapupahtreport").click(function()
				{
					$("#formcontainer").load("forms/frmagentwrapupaht.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #agentwrapupahtreportdesc");
				}
				);
				$("#agentwrapupdnisreport").click(function()
				{
					$("#formcontainer").load("forms/frmagentwrapupdnis.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #agentwrapupadnisreportdesc");
				}
				);
				$("#extensionsummaryreport").click(function()
				{
					$("#formcontainer").load("forms/frmextensionsummaryreport.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #extensionsummaryreportdesc");
				}
				);
				 $("#extensionsummaryreport_hourly").click(function()
                                {
                                        $("#formcontainer").load("forms/frmextensionsummaryreport_hourly.php");
                                        //load the description
                                        $("#reportdescription").load("forms/desc/reportdescription.php #extensionsummaryhourlyreportdesc");
                                }
                                );
				$("#queuesummaryreport").click(function()
				{
					$("#formcontainer").load("forms/frmqueuesummaryreport.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #queuesummaryreportdesc");
				}
				);
				$("#queuesummarydnisreport").click(function()
				{
					$("#formcontainer").load("forms/frmqueuesummarydnisreport.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #queuesummaryreportdesc");
				}
				);		
				$("#queuehourlyreport").click(function()
				{
					$("#formcontainer").load("forms/frmqueuehourlyreport.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #queuehourlyreportdesc");
				}
				);
				$("#agentproductivityreport").click(function()
				{
					$("#formcontainer").load("forms/frmagentproductivity.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #agentproductivityreportdesc");
				}
				);
				$("#agentstatusoccupancyreport").click(function()
				{
					$("#formcontainer").load("forms/frmagentstatusoccupancy.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #agentstatusoccupancyreportdesc");
				}
				);
				$("#dnisreport").click(function()
				{
					$("#formcontainer").load("forms/frmdnis.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #dnisreportdesc");
				}
				);
				$("#dnisreport_hourly").click(function()
				{
					$("#formcontainer").load("forms/frmdnis_hourly.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #dnishourlyreportdesc");
				}
				);
				$("#callarrivalreport").click(function()
				{
					$("#formcontainer").load("forms/frmcallarrival.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #callarrivalreportdesc");
				}
				);

				$("#queuereport").click(function()
				{
					$("#formcontainer").load("forms/frmqueue.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #queuereportdesc");
				}
				);
                                $("#agentperformanceinboundmonthlyreport").click(function()
				{
					$("#formcontainer").load("forms/frmagentperformanceinboundmonthly.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #agentperformanceinboundmonthlyreportdesc");
				}
				);
                                $("#agentproductivitymonthlyreport").click(function()
				{
					$("#formcontainer").load("forms/frmagentproductivitymonthly.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #agentproductivitymonthlyreportdesc");
				}
				);
                                $("#agentproductivityperagentreport").click(function()
				{
					$("#formcontainer").load("forms/frmagentproductivityperagent.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #agentproductivitymonthlyreportdesc");
				}
				);
                                $("#agentproductivitymonthlyperagentreport").click(function()
				{
					$("#formcontainer").load("forms/frmagentproductivitymonthlyperagent.php");
					//load the description
					$("#reportdescription").load("forms/desc/reportdescription.php #agentproductivitymonthlyreportdesc");
				}
				);
			}
			);
			
			/**
			 * accordion by joseph ian farillas - 090710
			 * @param {String} id
			 * @param {String} divID
			 * @param {String} divIndicator
			 */
			function hidelinks(id, divID, divIndicator)
			{
				var divIndicatorVal = $("#" + divIndicator).val();
				switch (divIndicatorVal)
				{
					case "0":
					    $("#" + id).css("list-style-image", "url('images/minus.png')");
						$(divID).slideToggle('slow');
						$("#" + divIndicator).val("1");
					break;
					case "1":
					    $("#" + id).css("list-style-image", "url('images/plus.png')");
						$(divID).slideToggle('slow');
						$("#" + divIndicator).val("0");
					break;
				}
			}
			
</script>
<div id="leftnavcont" style="padding:5px;width:240px;float:left;" class="ui-widget-content">
<div id="leftnav" >
	<h3><a href="#">&nbsp;&nbsp;&nbsp;AGENT REPORTS</a></h3>
    <div>
        <li class="leftnavcatsub"><a href="#" class="reportLink" id="agentloginlogoutreport">Agent Login/Logout Report</a></li>
        <li class="leftnavcatsub"><a href="#" class="reportLink" id="extensionsummaryreport">Extension Summary Report</a></li>
 	<!--<li class="leftnavcatsub"><a href="#" class="reportLink" id="extensionsummaryreport_hourly">Extension Summary Hourly Report</a></li>-->
        <li class="leftnavcatsub"><a href="#" class="reportLink" id="agentperformanceinboundreport">Agent Performance Report</a></li>
        <li class="leftnavcatsub"><a href="#" class="reportLink" id="agentperformanceinboundmonthlyreport">Agent Performance Monthly Report</a></li>
        <li class="leftnavcatsub"><a href="#" class="reportLink" id="agentwrapupreport">Agent Wrap-up Report</a></li>
       <!-- <li class="leftnavcatsub"><a href="#" class="reportLink" id="agentwrapupahtreport">Agent Wrap-up with AHT Report</a></li>-->
        <li class="leftnavcatsub"><a href="#" class="reportLink" id="agentwrapupdnisreport">Agent Wrap-up DNIS Report</a></li>
        <li class="leftnavcatsub"><a href="#" class="reportLink" id="agentproductivityreport">Agent Productivity Report</a></li>
        <li class="leftnavcatsub"><a href="#" class="reportLink" id="agentproductivitymonthlyreport">Agent Productivity Monthly Report</a></li>
        <!--<li class="leftnavcatsub"><a href="#" class="reportLink" id="agentproductivityperagentreport">Agent Productivity per Agent Report</a></li>-->
        <!--<li class="leftnavcatsub"><a href="#" class="reportLink" id="agentproductivitymonthlyperagentreport">Agent Productivity Monthly per Agent Report</a></li>-->
        <li class="leftnavcatsub"><a href="#" class="reportLink" id="agentstatusoccupancyreport">Agent Status and Occupancy Report</a></li>
        <li class="leftnavcatsub"><a href="#" class="reportLink" id="agentstatusdetailreport">Agent Status Detail Report</a></li>
    </div>
    <h3><a href="#">&nbsp;&nbsp;&nbsp;QUEUE REPORTS</a></h3>
    <div>
        <li class="leftnavcatsub"><a href="#" class="reportLink" id="queuesummaryreport">Queue Summary Report</a></li>
        <li calss="leftnavcatsub"><a href="#" class="reportLink" id="queuesummarydnisreport">Queue Name Summary Report</a></li>
		<li class="leftnavcatsub"><a href="#" class="reportLink" id="queuehourlyreport">Queue Hourly Report</a></li>	
    </div>
    <!--<h3><a href="#">&nbsp;&nbsp;&nbsp;ABANDON CALL REPORTS</a></h3>
    <div>
    </div> -->
    <h3><a href="#">&nbsp;&nbsp;&nbsp;DNIS REPORTS</a></h3>
    <div>
    	<li class="leftnavcatsub"><a href="#" class="reportLink" id="dnisreport">DNIS Report</a></li>
	<li class="leftnavcatsub"><a href="#" class="reportLink" id="dnisreport_hourly">DNIS Hourly Report</a></li>
	<!--<li class="leftnavcatsub"><a href="#" class="reportLink" id="callarrivalreport">Call Arrivals Report</a></li>-->
    </div>
</div>
</div>
