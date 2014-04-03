<?php
	require_once('config.php');
	require_once('classpagecommon.php');
	$pageCommon = new PageCommon();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Admin Reports</title>
		<meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">
        <script language="javascript" type="text/javascript" src="javascript/common.js"></script>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
        <script language="javascript" type="text/javascript" src="javascript/chat/chatCIS.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/keepalivereports.js"></script>
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
        <script type="text/javascript">
			function onReportLoad() {
				startKeepAlive();
				setTenantID(<?php echo $_SESSION['WEB_ADMIN_TENANTID']; ?>);
				document.getElementById( "divcontainer" ).style.height = (screen.availWidth - 300 ) + "px";
			}
		</script>
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/admin.css" />
		<link rel="stylesheet" type="text/css" href="css/ui.core.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery.datepick.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />
        <link rel="stylesheet" type="text/css" href="javascript/jqueryui/css/redmond/jquery-ui-1.8.4.custom.css" />
	</head>
	<body onload="assignUsername('<?php echo $_SESSION['WEB_ADMIN_USER'] ?>',<?php echo $_SESSION['WEB_ADMIN_USERID']; ?>); onReportLoad();">

	<div class="header">
		<?php
			$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']);
			$pageCommon->displayNavigation('reports', '');
							   // $pageCommon->displayChatBoxList($_SESSION['WEB_ADMIN_SESSION'],$_SESSION['WEB_ADMIN_USER'],$_SESSION['WEB_ADMIN_USERID']);
		?>
	</div>

