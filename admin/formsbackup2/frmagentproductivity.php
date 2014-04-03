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

		//for start date field
		$("#report_date").focus(function(){
			($(this).val() != "") ? $("#btn_report_date").attr("disabled", false) : $("#btn_report_date").attr("disabled", true);
		});

		//date picker
		$("#report_date").datepick({
			                       onSelect: function() {(($("#report_date").val() != "") && ($("#report_date2").val() != "")) ? $("#btn_report_date").attr("disabled", false) : $("#btn_report_date").attr("disabled", true);},
			                       rangeSelect: true,
								   showTrigger: '<img onchange="return setFocus(\'report_date\');" onclick="return generateEnabled(\'btn_report_date\');" border="0" src="images/be_calendar.gif" class="trigger" id="calImg" style="padding-left:10px;">'});

		// for end date field
		$("#report_date2").focus(function(){
			($(this).val() != "") ? $("#btn_report_date").attr("disabled", false) : $("#btn_report_date").attr("disabled", true);
		});

		//date picker
		$("#report_date2").datepick({
			                       onSelect: function() {(($("#report_date").val() != "") && ($("#report_date2").val() != "")) ? $("#btn_report_date").attr("disabled", false) : $("#btn_report_date").attr("disabled", true);},
			                       showTrigger: '<img onchange="return setFocus(\'report_date2\');" onclick="return generateEnabled(\'btn_report_date\');" border="0" src="images/be_calendar.gif" class="trigger" id="calImg" style="padding-left:10px;">'});


	}
	);

	function generateReportDate(id)
	{
		//nowGenerateReports(id, id2);
		dateRange = $("#" + id).val().split(" - ");
		$.post("includes/adminreports.php",
		{
			func: "validateDateRange",
			/*startDate: $("#" + id).val(),
			endDate: $("#" + id2).val()*/
			startDate: dateRange[0],
			endDate: dateRange[1]
		},
		function (data)
		{
			return (data != "1") ?  withErrorPage(data, 'validateError') :  nowGenerateReports(dateRange[0], dateRange[1]);
		}
		);
	}

	function withErrorPage(data, validateError)
	{
		$("#" + validateError).html(data);
		//open a dialog
		//compute for center screen
		centerScreen = screen.availWidth / 2;
		dialogSizeDivide = 275 / 2;
		//get the left size for the dialog
		getLeftSize = centerScreen - dialogSizeDivide;
		$.blockUI({
		           message: $("#" + validateError),
				   css: {
				          width: '275px',
						  left: getLeftSize
				   },
				   overlayCSS: {
				   	      cursor: 'default'
				   }
				 });
		//when close button was click, close all the dialogs
		$("#close").click(function()
		{
			$.unblockUI();
			$("#" + validateError).html('');
			return false;
		}
		);
		return this;
	}

	/**
	 * Now, load the report
	 */
	function nowGenerateReports(id, id2)
	{
		// Now, load the report
		//start date
		reportDate = id;
		dtReportYear = reportDate.substring(6);
		dtReportMonth = reportDate.substring(0, 2);
		dtReportDay = reportDate.substring(3, 5);
		dtReport = dtReportYear + "-" + dtReportMonth + "-" + dtReportDay;

		//end date
		reportDate2 = id2;
		dtReportYear2 = reportDate2.substring(6);
		dtReportMonth2 = reportDate2.substring(0, 2);
		dtReportDay2 = reportDate2.substring(3, 5);
		dtReport2 = dtReportYear2 + "-" + dtReportMonth2 + "-" + dtReportDay2;

		nTenantID = <?php echo $_SESSION['WEB_ADMIN_TENANTID'] ?> ;
		/*
		$('.btn_generate').openDOMWindow({

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
			windowSourceURL: baseURI + '/birt-viewer-2_6_1/frameset?__report=reports/agent_productivity.rptdesign&tenantid=' + nTenantID + '&startdate=' + dtReport + '&enddate=' + dtReport2,
			loader:1,
			loaderImagePath:'images/preloader.png',
			loaderHeight:screen.availHeight - 122 - 202,
			loaderWidth:screen.availWidth - 257
		});
		*/
		//open in new tab
        window.open(baseURI + '/birt-viewer-2_6_1/frameset?__report=reports/agent_productivity.rptdesign&tenantid=' + nTenantID + '&startdate=' + dtReport + '&enddate=' + dtReport2);

		//$('.reportLink').closeDOMWindow({anchoredClassName:'exampleWindow6',eventType:'click'});
		//unload report for click event;
		//$("#" + id + ", #" + id2).closeDOMWindow({eventType:'click'});
		//$(".report_date").closeDOMWindow({eventType:'click'});
		//unload report for focus event
		//$("#" + id + ", #" + id2).closeDOMWindow({eventType:'focus'});
		//$("#report_date").closeDOMWindow({eventType:'focus'});

		//disable the button
		//$("#btn_report_date").attr("disabled", true);
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
		<tr><td colspan="2"><b>Agent Productivity Report</b>&nbsp;&nbsp;&nbsp;</td><td></td></tr>
        <tr><td>&nbsp;&nbsp;&nbsp;</td>
        	<td>Date Range:
				<input type="text" name="report_date" id="report_date" class="reportdate"
                	onclick="return generateEnabled('btn_report_date');" readonly />
            </td>
            <td align="right">&nbsp;&nbsp;&nbsp;
            	<button name="btn_report_date" id="btn_report_date" class="btn_generate"
                    	onclick="return generateReportDate('report_date');">Generate</button>
            </td>
        </tr>
    </table>
</div>
<div id="validateError"></div>