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
	require_once('includes/classextensions.php');
	require_once('includes/classextension.php');
	require_once('includes/classpagecommon.php');
	
	if ( !isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		header("Location:login.php");
	}
	
	$pageCommon = new PageCommon();
	$extensions = new Extensions();
	$nExtensionCount = $extensions->getExtensionsCount();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Extensions</title>
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepalive.js"></script>
</head>

<body onload="startKeepAlive();">
<?php
	if ( isset( $_SESSION['WEB_ADMIN_LNAME'] ) ) {
		echo '<p>Name   : ' . $_SESSION['WEB_ADMIN_FNAME'] . ' ' . $_SESSION['WEB_ADMIN_LNAME'] . '</p>';
		echo '<p>Tenant : ' . $_SESSION['WEB_ADMIN_TENANT'] . '</p>';
	}  
	
	$pageCommon->displayMenu($_SESSION['WEB_ADMIN_TENANTID']);
?>
<h2>List of Extensions</h2>

<p>Total number of extensions: <?php echo $nExtensionCount; ?></p>
<a href="extendetails.php?type=add">Add Extension</a>
<table>
	<tr>
    	<td>Tenant</td>
        <td>Name</td>
        <td>Caller ID</td>
        <td>Secret</td>
        <td>Edit</td>
        <td>Disable</td>
    </tr>
    <?php
		if ( $nExtensionCount > 0 ) {
			// loop here
			$arrExtension = $extensions->getExtensions(0, 50);
			for ( $index = 0; $index < count($arrExtension); $index++ ) {
				echo '<tr><td>' . $arrExtension[$index]->getTenantName() . '</td>';
				echo '<td>' . $arrExtension[$index]->getExtensionName() . '</td>';
				echo '<td>' . $arrExtension[$index]->getCallerID() . '</td>';
				echo '<td>' . $arrExtension[$index]->getSecret() . '</td>';
				echo '<td><a href="extendetails.php?type=edit&extenid=' . $arrExtension[$index]->getExtensionID() . '">Edit</a></td>';
				echo '<td><a href="extendetails.php?type=delete&extenid=' . $arrExtension[$index]->getExtensionID() . '">Delete</a></td></tr>';
			}
		}
	?>
</table>

</body>
</html>