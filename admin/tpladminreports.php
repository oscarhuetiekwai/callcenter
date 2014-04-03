
<?php
//no  cache headers
header("Expires: Mon, 26 Jul 2012 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<?php
    session_start();
    require('includes/adminreports.php');

	AdminReports::getObject()->verifyLogin();
    echo AdminReports::getObject()->header();
	echo AdminReports::getObject()->leftNav();
?>


<script language="JavaScript">
	//baseURI = "<?php echo BASE_URI;?>";


	$(document).ready(function()
	{
                baseURI = "http://" + window.location.hostname + ":8080";


		resizeDivReportContent();
	});

	function resizeDivReportContent()
	{
		var divcontainer = document.getElementById("divcontainer1");
		var divleftnavcont = document.getElementById("leftnavcont");

		//divcontainer.style.top = divleftnavcont.style.top;
		divcontainer.style.height = (screen.availHeight - 270) + "px"; // divleftnavcont.style.height;
		divcontainer.style.width = screen.availWidth - divleftnavcont.style.width;
	}
</script>
    <!-- content body -->
        <div id="divcontainer1" >
        	 <!-- form container -->
			 <div id="formcontainer" style="height:50px;"></div>
			 <!-- end of form container -->
			 <hr />
             <div id="reportdescription"></div>
			 <!-- Report Graphs -->
			 <!--<div id="reportscontainer" class="divreportscontainer">
			 	    <div id="reportdescription">

			 	    </div> -->
                    <script type="text/javascript">

						 /**
						  * report area template properties
						  */
							var wt = screen.availWidth;
							var ht = screen.availHeight;

							//unload the other reports
							$(".extensionsummaryreport, .queuesummaryreport, .queuehourlyreport, .agentperformanceinBoundreport, .agentperformanceoutboundreport, .agentloginlogoutreport, .agentperformanceblendingreport, .agentproductivityreport, .agentstatusoccupancyreport, .agentqueuedistributioninboundandouboundreport, .agentwrapupreport, .agentcallbackreport, .agentstatuschangereport, .dnisreport, .queuereport, .queuewrapupreport, .inboundcalbackreport, .inboundabandonedcallreport, .outboundcampaignreport, .outboundcampaignperformance, .outboundcampaignwrapupreport, .outboundcampaigncallbackreport, .IVRselectionreport, .trunkoccupancyreport").closeDOMWindow({eventType:'click'});

							//function for left panel height
							function screenHeightDiv(htt)
							{
								return parseInt(htt) - 150;
							}

							//function for width
							function screenWidthDiv(wtt)
							{
								return parseInt(wtt) - 305;
							}

					</script>
			 	<!--
			 </div> -->
			 <!-- end of report graphs -->
	    </div>
	<!-- end of content body -->

<!-- start chat -->
<?php include("chat/chat.php"); ?>
<!-- end chat -->
<!-- End:   DIV Windows -->
</body>
</html>
<?php include("chat/javascript.php"); ?>

<?php //echo AdminReports::getObject()->footer(); ?>
