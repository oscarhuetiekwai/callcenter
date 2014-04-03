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
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepaliveadminvqueue.js"></script>
<script language="javascript" type="text/javascript">
	
</script>
</head>

<body onload="startKeepAlive();setTenantID(<?php echo $_SESSION['WEB_ADMIN_TENANTID']; ?>);" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php 
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']); 
	$pageCommon->displayNavigation('view', '');
?>

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    	<td width="2%">&nbsp;</td>
    	<td valign="top" width="98%">
        	<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
            	<tr valign="top">
                	<td width="30%">
                        <p><a href="#" class="morelink">+ Call Graph</a></p>
                        <div id="divgraph" class="divcallstats">
                        	<img id="imggraph" />
                        </div>
                    </td>
                    <td width="70%">
                        <p><a href="#" class="morelink">+ Call Data</a></p>
                        <div id="divdata" class="divuserstats">
                        </div>
                    </td>
            	</tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>