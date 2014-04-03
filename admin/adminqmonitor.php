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
	require_once('includes/classcalls.php');
	require_once('includes/classcall.php');
	require_once('includes/classpagecommon.php');

	
	if ( !isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		header('location:login.php');
	}
	
	$calls = new Calls();
	$pageCommon = new PageCommon();
	$szUsername = '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administration Page</title>
<!--
<link rel="stylesheet" type="text/css" href="javascript/jqueryui/css/redmond/jquery-ui-1.8.4.custom.css" />
----->
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" href="css/calendar.css" />

<style type="text/css">
.classDownload a:link {text-decoration: none; color:black;}
.classDownload a:visited {text-decoration: none; color:black;}
.classDownload a:active {text-decoration: none; color:black;}
.classDownload a:hover {text-decoration: underline; color: red;}

.thtable {
background-image:-webkit-linear-gradient(top,maroon,#a14444);
background-image:-moz-linear-gradient(top,maroon,#a14444);
background-image:-o-linear-gradient(top,maroon,#a14444);
color:white;
}

.buttonstyle {
background-image:-webkit-linear-gradient(top left,maroon,maroon);
background-image:-moz-linear-gradient(top left,maroon,maroon);
background-image:-o-linear-gradient(top left,maroon,maroon);
border-style:none;
color:white;
border-radius:5px;
box-shadow:0px 2px 2px grey;
-webkit-transition:all .5s;
-moz-transition:all .5s;
-o-transition:all .5s;
cursor:pointer;
}
</style>
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
<script language="javascript" type="text/javascript" src="javascript/chat/chatCIS.js"></script>
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepaliveqmonitor.js"></script>
<script language="javascript" type="text/javascript" src="javascript/PopupWindow.js"></script>

<!---
<script language="javascript" type="text/javascript" src="javascript/jqueryui/js/jquery-1.4.2.min.js"></script>
<script language="javascript" type="text/javascript" src="javascript/jqueryui/js/jquery-ui-1.8.4.custom.min.js"></script>
---->
<script src="javascript/jquery-1.9.1.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css" />
<script src="javascript/jquery-ui.js"></script>

<script language="javascript" type="text/javascript" src="javascript/timepicker.js"></script>
<script type="text/javascript" src="javascript/jquery.ui.core.js" ></script>
<script type="text/javascript" src="javascript/jquery.blockUI.js" ></script>
<script type="text/javascript" src="javascript/jquery.DOMwindow.js" ></script>
<script type="text/javascript" src="javascript/jquery.selectboxes.js" ></script>
<script type="text/javascript" src="javascript/jquery.datepick.js" ></script>
<script type="text/javascript" src="javascript/jquery.autocomplete.js" ></script>
<script type="text/javascript" src="javascript/jquery.autocomplete.min.js" ></script>

<?php $pageCommon->displayScriptChangePwd(); ?>

<script language="javascript" type="text/javascript">

function playRecord(recordingFile) {
	var divflashobject = document.getElementById('flashobject');
	
	if ( divflashobject ) {
		var lnkDownloadFile = document.getElementById("lnkDownloadFile");
		
		divflashobject.innerHTML = '<object type="application/x-shockwave-flash" id="player1" allowscriptaccess="always" ' +
			'allowfullscreen="true" data="flash/OriginalMusicPlayer.swf" width="225" height="86"><param name="movie"  ' +
			'value="flash/OriginalMusicPlayer.swf" /><param name="FlashVars" value="mediaPath=flash/audiopass.php?filename=' +
			recordingFile + '" /></object>';
		
		lnkDownloadFile.setAttribute("href", "flash/audiopass.php?filename=" + recordingFile);
			
		//divflashobject.innerHTML = '<object type="application/x-shockwave-flash" id="player1" allowscriptaccess="always" ' +
		//	'allowfullscreen="true" data="flash/OriginalMusicPlayer.swf" width="225" height="86"><param name="movie"  ' +
		//	'value="flash/OriginalMusicPlayer.swf" /><param name="FlashVars" value="mediaPath=flash/t1-Operator-1281443135.14.wav" /></object>';
	}
	
	Popup.showModal('flashPlayer');
	
}

function onSearch() {
	var szdate1 = document.getElementById("date1").value;
	var szdate2 = document.getElementById("date2").value;
	var szusername = document.getElementById("username").value;
	var szext = document.getElementById("ext").value;
	var sztxtcallerid = document.getElementById("txtcallerid").value;
	
	addHttpRequest("adminqmonitor:search", "date1=" + szdate1 + "&date2=" + szdate2 + "&agentid=" + szusername + "&ext=" + 
				   szext + "&callerid=" + sztxtcallerid );
	
}

function onPageClick(nPage)  {
	var szdate1 = document.getElementById("date1").value;
	var szdate2 = document.getElementById("date2").value;
	var szusername = document.getElementById("username").value;
	var szext = document.getElementById("ext").value;
	var sztxtcallerid = document.getElementById("txtcallerid").value;
	
	addHttpRequest("adminqmonitor:search", "date1=" + szdate1 + "&date2=" + szdate2 + "&agentid=" + szusername + "&ext=" + 
				   szext + "&callerid=" + sztxtcallerid + "&page=" + nPage);
}

function onLoad(nAdminTenantID) {
	startKeepAlive();
	$('#date1').datetimepicker( {
		showSecond: true,
		dateFormat: 'yy-mm-dd',
		timeFormat: 'hh:mm:ss'
		} );
	$('#date2').datetimepicker( {
		showSecond: true,
		dateFormat: 'yy-mm-dd',
		timeFormat: 'hh:mm:ss'
		} );
	setTenantID(nAdminTenantID);
}

</script>
</head>

<body onload="assignUsername('<?php echo $_SESSION['WEB_ADMIN_USER'] ?>',<?php echo $_SESSION['WEB_ADMIN_USERID']; ?>); onLoad(<?php echo $_SESSION['WEB_ADMIN_TENANTID']; ?>);" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php 
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']); 
	$pageCommon->displayNavigation('qmonitor', 'call status');
	
		
?>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    	<td width="15">&nbsp;</td>
    	<td valign="top" width="270">
        	<p><h2>Search Criteria's</h2></p>
        	<table id="tblsearch" cellspacing="2" cellpadding="2" style="-moz-border-radius: 10px;-webkit-border-radius: 5px; padding: 0px;width:230px;border:1px solid maroon;padding-left:5px">
            	<tr>
                	<td colspan="2">&nbsp;</td>
                </tr>
            	<tr>
                	<td colspan="2"><b>Date Range</b></td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Start</td>
                    <td><input type="text" id="date1" name="date1" value="" style="width: 140px;" /></td>
                </tr>
                <tr>
                	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;End</td>
                    <td><input type="text" id="date2" name="date2" value="" style="width: 140px;" /></td>
                </tr>
                <tr>
                	<td><b>Agent</b></td>
                    <td>
                    	<select  id="username" name="username" style="width: 140px;">
    						<?php echo $calls->getAllAgentName(1); ?>

                        </select>
                    </td>
                </tr>
                <tr>
                	<td><b>Ext</b></td>
                    <td>
                    	<select id="ext" name="ext"  style="width: 140px;">
                			<?php echo $calls->getExt(1); ?>
                		 </select>
                    </td>
                </tr>
                <!--<tr>
                	<td><b>Wrapup</b></td>
                    <td>
                    	<select id="wrapup" name="wrapup" style="width: 140px;">
                  			<?php echo $calls->getAllWrapupsOption($_SESSION['WEB_ADMIN_TENANTID']); ?>
                		</select> 
                    </td>
                </tr>-->
                <tr>
                	<td><b>Caller ID</b></td>
                    <td><input type="text" id="txtcallerid"  style="width: 140px;"/></td>
                </tr>
                <tr>
                	<td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                	<td align="center" colspan="2" style="border-top:1px solid #336699;height: 30px;">
                    	<input type="button" class="buttonstyle" id="btnsearch" value="  Search  " onclick="onSearch();" />
                    </td>
                </tr>
            </table>
      	</td>
        
  		<td valign="top">
        
			<p><h2>List of Calls</h2></p>
			
			
            <div style="background-color:white;-moz-border-radius: 10px;-webkit-border-radius: 5px; padding: 0px; height:340px; width:700px; border:1px solid maroon;">
	
            	<table>
                  <tr class="thtable">
                        <th  width=\"27\">
                        <input type="checkbox" name="Check_ctr" value="yes" onClick="Check(document.myform.check_list)">
                        </th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:80px;">Date</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:60px;">Time</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:120px;">Agent</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:80px;">Ext</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:100px;">Caller No</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:120px;">Queue</th>
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:40px;">Duration/s</th>
                        <!--<th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:80px;">Wrapup</th>-->
                        <th style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px; width:40px;">Play</th>
                  </tr>
             	  <tr>
                  	<td colspan="9">
                    	<div id="divqmonitor" style="height:320px; overflow:auto;">
                        </div>
                    </td>
				  </tr>
               
          </table>
          <div id="divnavigation">
          </div>
          </div>
        </td>
    </tr>
</table>

<!-- Start: Div Player -->
<div id="flashPlayer" style="border:3px solid black; background-color:#9999ff; padding:25px; font-size:150%; text-align:center; display:none;">
	<input type="hidden" id="txtRecordingFile" value="" />
	<table width="100%">
    	<tr style="text-align:center;"><td>
        	<div id="flashobject"></div>
        </td></tr>
         <tr style="text-align:center;"><td>
         	<a class="classDownload" id="lnkDownloadFile" href="">Download File</a>
         </td></tr>
         <tr style="text-align:center;"><td>
        	<input type="button" value="Close" onClick="Popup.hide('flashPlayer')" />
        </td></tr>
    </table>
	<!-- <object type="application/x-shockwave-flash" id="player1" allowscriptaccess="always" allowfullscreen="true" data="flash/OriginalMusicPlayer.swf" width="225" height="86"><param name="movie" value="flash/OriginalMusicPlayer.swf" /><param name="FlashVars" value="mediaPath=flash/song.mp3" /></object> -->
	
</div>
<!-- End  : Div Player -->
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
<?php include("chat/javascript.php"); ?>