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
<title>Administration License Page</title>
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="javascript/jqueryui/css/redmond/jquery-ui-1.8.4.custom.css" />
<link rel="stylesheet" type="text/css" href="javascript/flexigrid/css/flexigrid/flexigrid.css" />
<link rel="stylesheet" type="text/css" href="css/datatables/demo_table.css" />
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
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepaliveadminlicense.js"></script>
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
<script type="text/javascript" src="javascript/jquery.dataTables.min.js" ></script>
<script type="text/javascript" src="javascript/flexigrid/flexigrid.js"></script>
<?php $pageCommon->displayScriptChangePwd(); ?>
</head>
<body onload="startKeepAlive();setTenantID(<?php echo $_SESSION['WEB_ADMIN_TENANTID']; ?>);" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php 
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']); 
	$pageCommon->displayNavigation('admin', 'license');
?>

<?php $pageCommon->displayDivChangePwd(); ?>
<!-- End:   DIV Windows -->
</body>
</html>