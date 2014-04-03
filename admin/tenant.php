
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
	require_once('includes/classtenants.php');
	require_once('includes/classtenant.php');
	require_once('includes/classpagecommon.php');
	
	if ( !isset( $_SESSION['WEB_ADMIN_SESSION'] ) ) {
		header("Location:login.php");
	}
	
	if ( isset( $_SESSION['WEB_ADMIN_TENANTID'] ) ) {
		if ( $_SESSION['WEB_ADMIN_TENANTID'] != "0" ) {
			header("Location:admin.php");
		}
	}
	
	$pageCommon = new PageCommon();
	$tenants = new Tenants();
	$ntenantCount = $tenants->getTenantsCount();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tenants</title>
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
<h2>List of Tenants</h2>

<p>Total number of tenants: <?php echo $ntenantCount; ?></p>
<a href="tenantdetails.php?type=add">Add Tenant</a>
<table>
	<tr>
    	<td>Name</td>
        <td>Contact Person</td>
        <td>Mobile Phone</td>
        <td>Office Phone</td>
        <td>Home Phone</td>
        <td>Edit</td>
        <td>Disable</td>
    </tr>
    <?php
		if ( $ntenantCount > 0 ) {
			// loop here
			$arrTenant = $tenants->getTenants(0, 50);
			for ( $index = 0; $index < count($arrTenant); $index++ ) {
				echo '<tr><td>' . $arrTenant[$index]->getTenantName() . '</td>';
				echo '<td>' . $arrTenant[$index]->getContactPerson() . '</td>';
				echo '<td>' . $arrTenant[$index]->getMobilePhone() . '</td>';
				echo '<td>' . $arrTenant[$index]->getOfficePhone() . '</td>';
				echo '<td>' . $arrTenant[$index]->getHomePhone() . '</td>';
				echo '<td><a href="tenantdetails.php?type=edit&tenantid=' . $arrTenant[$index]->getTenantID() . '">Edit</a></td>';
				echo '<td><a href="tenantdetails.php?type=disable&tenantid=' . $arrTenant[$index]->getTenantID() . '">Disable</a></td></tr>';
			}
		}
	?>
</table>

</body>
</html>