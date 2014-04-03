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
	require_once('includes/config.php');
	require_once('includes/errhandler.php');
	require_once('includes/classpagecommon.php');

	if ( !isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		header('location:login.php');
	}

	$pageCommon = new PageCommon();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administration Page</title>
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="javascript/jqueryui/css/redmond/jquery-ui-1.8.4.custom.css" />
<style>
		label, input { display:block; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; }
		fieldset { padding:0; border:0; margin-top:25px; }
		h1 { font-size: 1.2em; margin: .6em 0; }
		div#users-contain { width: 350px; margin: 20px 0; }
		div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
		div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>
<script language="javascript" type="text/javascript" src="javascript/chat/chat.js"></script>
<script language="javascript" type="text/javascript" src="javascript/chat/chatCIS.js"></script>
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepaliveadmin.js"></script>
<script type="text/javascript" src="FusionCharts/FusionCharts.js"></script>
<script type="text/javascript" src="javascript/jquery-1.3.2.js" ></script>
<script type="text/javascript" src="javascript/jquery-1.5.1.js" ></script>
<script type="text/javascript" src="javascript/screen.js" ></script>
<script type="text/javascript" src="javascript/jquery-ui-1.7.2.custom.min.js" ></script>
<script type="text/javascript" src="javascript/jquery.ui.core.js" ></script>
<script type="text/javascript" src="javascript/jquery.blockUI.js" ></script>
<script type="text/javascript" src="javascript/jquery.DOMwindow.js" ></script>
<script type="text/javascript" src="javascript/jquery.selectboxes.js" ></script>
<script type="text/javascript" src="javascript/jquery.datepick.js" ></script>
<script type="text/javascript" src="javascript/jquery.autocomplete.js" ></script>
<script type="text/javascript" src="javascript/jquery.autocomplete.min.js" ></script>
<?php $pageCommon->displayScriptChangePwd(); ?>
</head>

<body onload="assignUsername('<?php echo $_SESSION['WEB_ADMIN_USER'] ?>',<?php echo $_SESSION['WEB_ADMIN_USERID']; ?>); startKeepAlive();setTenantID(<?php echo $_SESSION['WEB_ADMIN_TENANTID']; ?>);" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']);
	$pageCommon->displayNavigation('view', '');
?>

<br />

<p><a href="#" class="morelink" onclick="runEffect('divqueueborder'); return false;" >+ Queue</a></p>
<div id="divqueueborder">
	<table cellspacing="1" cellspacing="1">
    	<tr>
        	<td style="vertical-align:top;">
                <table cellspacing="1" cellpadding="1" style="background-color: #a8df7e; padding: 0px; vertical-align:top;-moz-border-radius: 10px;-webkit-border-radius: 5px; padding: 0px;">
                    <tr>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:60px;">DNIS</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:120px;">Queue</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:100px;">Caller ID</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:100px;">Caller Name</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:80px;">Status</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px;">Duration</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:100px;">Agent</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px">Agent ID</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:60px">Agent Ext</th>
                    </tr>
                    <tr>
                        <td colspan="9">
                            <div id="divqueue" style="height:250px; overflow:auto;">
                            </div>
                        </td>
                    </tr>
                </table>
        	</td>
            <td style="vertical-align:top;">
            	<div id="divqueuePieChart">
					FusionCharts
				</div>
                <script type="text/javascript">
                    var queuePieChart = new FusionCharts("FusionCharts/FCF_Pie3D.swf", "queuePieChart", "320", "280");
					var szPieData = "<graph pieSliceDepth='15' showBorder='1' numberSuffix='' showValues='0' showNames='1'>"
						//szPieData += ("<set name='Connected' value='1' color='00FF00' />");
						//szPieData += ("<set name='Queue' value='1' color='FF0000' />");
						szPieData += ("<set name='Idle' value='1' color='0000FF' />");
						szPieData += "</graph>";

                	queuePieChart.setDataXML(szPieData);
					queuePieChart.render("divqueuePieChart");
               </script>

            </td>
       	</tr>
    </table>
</div>
<br />
<div id="divqueuesummaryborder" class="divqueuesummary">
	<p><a href="#" class="morelink" onclick="runEffect('divqueuesummarydetails'); return false;" >+ Queue Summary Details</a></p>
	<div id="divqueuesummarydetails" >
    	<table cellspacing="0" cellpadding="0" style="background-color: #a8df7e; padding: 0px; vertical-align:top;-moz-border-radius: 10px;-webkit-border-radius: 5px; padding: 0px;">
        	<tr style="height:25px;">
                <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:120px;">Queue Name</th>
                <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:60px;">Calls Rcv</th>
                <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:60px;">Calls in Queue</th>
                <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:60px;">Calls<br/>Answered <br />&lt;=  SLA</th>
                <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:60px;">Calls<br/>Answered <br />&gt;
                SLA</th>
                <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:60px;">Calls <br/>Abandon <br/> &lt;= X secs</th>
                <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:60px;">Calls <br/>Abandon <br /> &gt; X secs</th>
                <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:60px;">Calls<br />Orphaned</th>
                <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:60px;">% Calls<br />Answered<br />&lt;= SLA</th>
                <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:60px;">% Calls<br /> Answered <br /> &gt; SLA</th>
                <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:60px;">% Calls<br />Abandon <br />&lt;= X Secs</th>
                <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:60px;">% Calls<br />Abandon <br />&gt; Secs</th>
                <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:60px;">% Calls<br />Orphaned</th>
                <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:70px;">Longest Call</th>
             </tr>
             <tr>
             	<td colspan="14">
                	<div id="divqueuesummary" style="height:180px; overflow:auto;">
                    </div>
                </td>
             </tr>
        </table>
    </div>
	<br />
    <p><a href="#" class="morelink"  onclick="runEffect('divqueuesummarygraph'); return false;">+ Queue Summary Graph</a></p>
	<div id="divqueuesummarygraph" style="display:none;">

        <div id="queueColumndiv">
                FusionCharts
        </div>

        <script language="JavaScript">
                var queueColumnChart = new FusionCharts("FusionCharts/FCF_MSColumn3D.swf", "queueColumnChart", "980", "400");
                //var strXML=generateXML(this.document.productSelector.AnimateChart.checked);
                queueColumnChart.setDataXML('');
                queueColumnChart.render("queueColumndiv");
        </script>

    </div>

</div>

<!-- Start: DIV Windows -->
<div id="modal" style="border:3px solid black; background-color:#9999ff; padding:25px; font-size:150%; text-align:center; display:none;">
	This is a modal popup!<br><br>
	<input type="button" value="OK" onClick="Popup.hide('modal')">
</div>
<div id="chat">
<?php // $pageCommon->displayDivChangePwd(); ?>
<?php //$pageCommon->displayChatBoxList($_SESSION['WEB_ADMIN_SESSION'],$_SESSION['WEB_ADMIN_USER'],$_SESSION['WEB_ADMIN_USERID']); ?>
</div>

<!-- start chat -->
<?php include("chat/chat.php"); ?>
<!-- end chat -->
<!-- End:   DIV Windows -->
</body>
</html>
<?php include("chat/javascript.php"); ?>