<?php
    session_start();
    require('adminreports.php');

	AdminReports::getObject()->verifyLogin();
    echo AdminReports::getObject()->header();
	echo AdminReports::getObject()->leftNav(); 
?>
<script language="JavaScript">
	baseURI = "<?php echo BASE_URI;?>";
</script>


    <!-- content body -->
        <div class="divcontainer">
        	 <!-- form container -->
			   <div class="formcontainer" id="formcontainer"></div>
			 <!-- end of form container -->
			 <br />
			 <hr />
			 <!-- Report Graphs -->
			 <div id="reportscontainer" class="divreportscontainer">
			 	    <div id="reportdescription">
			 	    	
			 	    </div>
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
			 	
			 </div>
			 <!-- end of report graphs -->
	    </div>
	<!-- end of content body 2-->
	
	

<?php //echo AdminReports::getObject()->footer(); ?>


