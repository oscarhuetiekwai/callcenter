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
<title>Administration - Settings Page</title>

<link type="text/css" href="javascript/jqueryui/css/redmond/jquery-ui-1.8.4.custom.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<style type="text/css">
			/*demo page css*/
			.divsettings body{ font: 62.5% "Trebuchet MS", sans-serif;}
			.divsettings #dialog_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative;}
			.divsettings #dialog_link span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}
			.divsettings ul#icons {margin: 0; padding: 0;}
			.divsettings ul#icons li {margin: 2px; position: relative; padding: 4px 0; cursor: pointer; float: left;  list-style: none;}
			.divsettings ul#icons span.ui-icon {float: left; margin: 0 4px;}
	</style>
<!-- <link rel="stylesheet" type="text/css" href="css/common.css" /> -->
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepaliveadmin.js"></script>
<script language="javascript" type="text/javascript" src="javascript/jqueryui/js/jquery-1.4.2.min.js"></script>
<script language="javascript" type="text/javascript" src="javascript/jqueryui/js/jquery-ui-1.8.4.custom.min.js"></script>
<script type="text/javascript" src="javascript/jquery.ui.core.js" ></script>
<script type="text/javascript" src="javascript/jquery.blockUI.js" ></script>
<script type="text/javascript" src="javascript/jquery.DOMwindow.js" ></script>
<script type="text/javascript" src="javascript/jquery.selectboxes.js" ></script>
<script type="text/javascript" src="javascript/jquery.datepick.js" ></script>
<script type="text/javascript" src="javascript/jquery.autocomplete.js" ></script>
<script type="text/javascript" src="javascript/jquery.autocomplete.min.js" ></script>
<?php $pageCommon->displayScriptChangePwd(); ?>


<script language="javascript" type="text/javascript">
	$(function() {
		$('#tabsettings').tabs();
	});
</script>
</head>

<body onload="startKeepAlive();setTenantID(<?php echo $_SESSION['WEB_ADMIN_TENANTID']; ?>);" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php 
	echo $_SERVER['REMOTE_ADDR'];
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']); 
	$pageCommon->displayNavigation('admin', 'settings');
?>

<br />
<div class="divsettings">
<div id="divbody" style="height:100%">
	<div id="tabsettings"  height:450px>
    	<ul>
        	<li><a href="#tabs-settings">Settings</a></li>
            <li><a href="#tabs-license">License</a></li>
        	<li><a href="#tabs-crm">CRM</a></li>
        </ul>
        <div id="tabs-settings">
        	Settings
        </div>
        <div id="tabs-license">
        	License
        </div>
        <div id="tabs-crm">
        	CRM
        </div>
    </div>
</div>
</div>
<?php $pageCommon->displayDivChangePwd(); ?>
</body>
</html>