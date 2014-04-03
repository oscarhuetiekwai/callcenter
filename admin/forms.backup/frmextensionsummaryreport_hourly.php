<?php
session_start();
?>
<script language="JavaScript">
	$(document).ready(function()
	{
		//disable the button
		$("#btn_report_date").attr("disabled", true);
		
		$('.reportLink').click(function()
		{
			$('.reportLink').closeDOMWindow({anchoredClassName:'exampleWindow6',eventType:'click'});
		})
		
		$("#report_date").focus(function(){
			($(this).val() != "") ? $("#btn_report_date").attr("disabled", false) : $("#btn_report_date").attr("disabled", true);
		});
		
		//date picker
		$("#report_date").datepick({
			                       onSelect: function() {($("#report_date").val() != "") ? $("#btn_report_date").attr("disabled", false) : $("#btn_report_date").attr("disabled", true);},
			                       showTrigger: '<img onchange="return setFocus(\'report_date\');" onclick="return generateEnabled(\'btn_report_date\');" border="0" src="images/be_calendar.gif" class="trigger" id="calImg" style="padding-left:10px;">'});	
	    
		
	}
	);
	
	function generateReportDate(id)
	{
		// Now, load the report
		reportDate = $("#" + id).val();
		dtReportYear = reportDate.substring(6);
		dtReportMonth = reportDate.substring(0, 2);
		dtReportDay = reportDate.substring(3, 5);
		startdate = dtReportYear + "-" + dtReportMonth + "-" + dtReportDay;
		nTenantID = <?php echo $_SESSION['WEB_ADMIN_TENANTID'] ?> ;
		$('.btn_generate').openDOMWindow({ 
									/*height:310, 
									width:screenWidthDiv(wt), 
									positionType:'absolute', 
									positionTop:268, 
									//eventType:'click', 
									positionLeft:285, 
									windowSource:'iframe', 
									windowSourceURL: baseURI + '/birt-viewer/frameset?__report=queue_summary_hourly.rptdesign&startdate=' + startdate,
									windowPadding:0, 
									loader:1,
									borderSize:0, 
									loaderImagePath:'images/preloader.png', 
									loaderHeight:screenHeightDiv(ht), 
									loaderWidth:screenWidthDiv(wt), 
									overlayOpacity:0, 
									overlay:0 */
			height:screen.availHeight - 122 - 202, 
			width: screen.availWidth - 257,
			positionType:'anchored', 
			anchoredClassName:'exampleWindow6', 
			anchoredSelector:'#reportdescription', 
			positionTop:-10, 
			positionLeft:252,
			borderSize: 1,
			windowPadding: 0,
			scrollbars: 0,
			//eventType:'click', 
			//draggable:1, 
			windowSource: 'iframe',
			windowSourceURL: baseURI + '/birt-viewer-2_6_1/frameset?__report=reports/ext_summary_hourly.rptdesign&tenantid=' + nTenantID + '&dtReport=' + startdate,
			loader:1, 
			loaderImagePath:'images/preloader.png', 
			loaderHeight:screen.availHeight - 122 - 202, 
			loaderWidth:screen.availWidth - 257 
									}
									);
		//unload report
		//$("#" + id + ", .virtualqueuereport").closeDOMWindow({eventType:'click'});
		//unload report
		//$("#report_date").closeDOMWindow({eventType:'focus'});
		
		//disable the button
		$("#btn_report_date").attr("disabled", true);
	}
	
	function generateEnabled(id)
	{
		//disable the button
		$("#" + id).attr("disabled", false);
	}
	
	//focus on the report date text field
	function setFocus(id)
	{
		//unload report
		$("#" + id).focus();
		
	}
	
</script>
<div id="divparameters">
	<table style="vertical-align:middle;">
		<tr><td colspan="2"><b>Extension / Agent Summary Hourly Report</b>&nbsp;&nbsp;&nbsp;</td><td></td></tr>
        <tr><td>&nbsp;&nbsp;&nbsp;</td>
        	<td>Report Date:
				<input type="text" name="report_date" id="report_date" class="formtext" onclick="return generateEnabled('btn_report_date');" readonly />
            </td>
            <td align="right">&nbsp;&nbsp;&nbsp;
            	<button name="btn_report_date" id="btn_report_date" class="btn_generate" onclick="return generateReportDate('report_date');">Generate</button>
            </td>
        </tr>
    </table>
</div>
<div id="validateError"></div>
