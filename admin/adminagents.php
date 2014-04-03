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
<script language="javascript" type="text/javascript" src="javascript/keepaliveadminagents.js"></script>
<script type="text/javascript" src="FusionCharts/FusionCharts.js"></script>

<script type="text/javascript" src="javascript/jquery-1.3.2.js" ></script>

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

<body onload="assignUsername('<?php echo $_SESSION['WEB_ADMIN_USER']; ?>',<?php echo $_SESSION['WEB_ADMIN_USERID']; ?>); startKeepAlive();setTenantID(<?php echo $_SESSION['WEB_ADMIN_TENANTID']; ?>);" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php 
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']); 
	$pageCommon->displayNavigation('view', 'agents');
?>

<br />

<p><a href="#" class="morelink" onclick="runEffect('divagentborder'); return false;" >+ Agent Real-Time View</a></p>
<div id="divagentborder">
	<table cellspacing="1" cellspacing="1">
    	<tr>
        	<td style="vertical-align:top;">
                <table cellspacing="1" cellpadding="1" style="background-color: #a8df7e; padding: 0px; vertical-align:top;-moz-border-radius: 10px;-webkit-border-radius: 5px; padding: 0px;">
                    <tr>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px;">Agent ID</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:120px;">Agent Name</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px;">Ext</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:60px;">Status</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px;">Time in Status</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:120px;">Queue</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:100px;">Caller ID</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:100px">Called ID</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px">Login Time</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px">Login Duration</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:60px">Last Status</th>
                    </tr>
                    <tr>
                        <td colspan="11">
                            <div id="divagentrealtime" style="height:250px; overflow:auto;">
                            </div>
                        </td>
                    </tr>
                </table>
        	</td>
       	</tr>
    </table>
</div>
<br />

<p><a href="#" class="morelink" onclick="runEffect('divagentsummaryborder'); return false;" >+ Agent Summary View</a></p>
<div id="divagentsummaryborder">
	<table cellspacing="1" cellspacing="1">
    	<tr>
        	<td style="vertical-align:top;">
                <table cellspacing="1" cellpadding="1" style="background-color: #a8df7e; padding: 0px; vertical-align:top;-moz-border-radius: 10px;-webkit-border-radius: 5px; padding: 0px;">
                    <tr>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px;">Agent ID</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:120px;">Agent Name</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px;">Ext</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:40px;">Total Calls Rcvd</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:40px;">Total Calls Ans</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:40px;">Total Calls M'sed</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:40px">Total Calls Abandon</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px">Total Call Out</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px">Total Talk Time</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px">Total Hold Time</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px">Total ACW Time</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px">Total Handling Time</th>
                        <!--<th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px">Total Prod Time</th>-->
                        <!--<th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px">Total Unprod Time</th>-->
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px">Avg Speed Ans</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px">Avg Talk Time</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px">Avg Hold Time</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px">Avg ACW Time</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px">AHT</th>
                        <!--<th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:50px">Team</th>-->
                    </tr>
                    <tr>
                        <td colspan="17">
                            <div id="divagentsummary" style="height:250px; overflow:auto;">
                            </div>
                        </td>
                    </tr>
                </table>
        	</td>
       	</tr>
    </table>
</div>

<p><a href="#" class="morelink" onclick="runEffect('divagentgraphborder'); return false;" >+ Agent Real-Time Graph</a></p>
<div id="divagentgraphborder">
	<table cellspacing="1" cellspacing="1">
    	<tr>
        	<td style="vertical-align:top;">
                <div id="divagentgraph">
                	FusionCharts
                </div>
                
                <script type="text/javascript">			
                	var agentBarChart = new FusionCharts("FusionCharts/FCF_MSColumn3D.swf", "agentBarChart", "980", "400");		   
					var szBarData = "<graph decimalPrecision='0' animation='0'>"
						szBarData += "<categories><category name='Agents Status' /></categories>";
						szBarData += ("<dataset seriesName='Available' color='00FF00' ><set value='0'/></dataset>");
						szBarData += ("<dataset seriesName='Ringing' color='FFFF00' ><set value='0'/></dataset>");
						szBarData += ("<dataset seriesName='Busy' color='FF0000' ><set value='0' /></dataset>");
						szBarData += ("<dataset seriesName='After Call Work' color='A52A2A' ><set value='0'/></dataset>");
						szBarData += ("<dataset seriesName='Others' color='0000FF' ><set value='0'/></dataset>");
						szBarData += "</graph>";
						
                		agentBarChart.setDataXML(szBarData);
                		agentBarChart.render("divagentgraph");
        		</script>
        	</td>
       	</tr>
    </table>
</div>
<br />

<div id="chat">
<?php $pageCommon->displayDivChangePwd(); ?>
<?php //$pageCommon->displayChatBoxList($_SESSION['WEB_ADMIN_SESSION'],$_SESSION['WEB_ADMIN_USER'],$_SESSION['WEB_ADMIN_USERID']); ?>
</div>

<!-- start chat -->
<?php include("chat/chat.php"); ?>
<!-- end chat -->
<!-- End:   DIV Windows -->
</body>
</html>
<script type="text/javascript" src="javascript/jquery-1.5.1.js" ></script>
<?php include("chat/javascript.php"); ?>