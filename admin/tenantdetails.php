<?php
    session_start();
	require_once('includes/config.php'); 
	require_once('includes/errhandler.php');
	require_once('includes/classtenants.php');
	require_once('includes/classtenant.php');
	require_once('includes/classpagecommon.php');
	
	$pageCommon = new PageCommon();
	$tenants = new Tenants();
	$errMessage = '';
	$transactionType = 'add';
	$tenantID = 0;
	$curTenant = null;
	
	if ( isset($_GET['tenantid']) ) {
		$tenantID = $_GET['tenantid'];
	} else if ( isset( $_POST['hdnTenantID'] ) ) {
		$tenantID = $_POST['hdnTenantID'];
	}
	

	if ( isset($_GET['type'] ) ) {
		$transactionType = $_GET['type'];
	} else if ( isset($_POST['hdnType'] ) ) {
		$transactionType = $_POST['hdnType'];
	}
	
	if ( isset( $_POST['btnSave'] ) ){
	
		$tenant = new Tenant( $tenantID, $_POST['txtTenantName'], $_POST['txtContactPerson'], $_POST['txtOfficePhone'],
			$_POST['txtHomePhone'], $_POST['txtMobilePhone'] );
		
		if ( $tenants->saveTenant( $tenant ) ) {
			header('Location:tenant.php');
		} else {
			$errMessage =  '<p>' . $tenants->getLastErrorMessage() . '</p>';
		}
		
	} else {
		if ( $tenantID > 0 ) {
			$curTenant = $tenants->getTenantInfo($tenantID);
			
			$_POST['txtTenantName'] = $curTenant->getTenantName();
			$_POST['txtContactPerson'] = $curTenant->getContactPerson();
			$_POST['txtMobilePhone'] = $curTenant->getMobilePhone();
			$_POST['txtOfficePhone'] = $curTenant->getOfficePhone();
			$_POST['txtHomePhone'] = $curTenant->getHomePhone();
			
		}
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tenant Details</title>
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
<h2>
<?php
    
		
	if ( $transactionType == 'add' ) {
		echo 'Add New Tenant';
	} else if ($transactionType == 'edit' ) {
		echo 'Edit Tenant "' . $_POST['txtTenantName'] . '"';
	} else if ($transactionType == 'disable' ) {
		echo 'Are you sure you want to disable tenant "' . $_POST['txtTenantName'] . '"?';
	}
	
?>
</h2>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="post" name="frmTenantDetails">
<input type="hidden" name="hdnType" id="hdnType" value="<?php echo $transactionType;  ?>" />
<input type="hidden" name="hdnTenantID" id="hdnTenantID" value="<?php echo $tenantID;  ?>" />
<table>
	<tr>
    	<td>Tenant Name:</td>
        <td><input type="text" id="txtTenantName" name="txtTenantName" value="<?php if ( isset($_POST['txtTenantName']) ) { echo $_POST['txtTenantName']; } ?>" /></td>
    </tr>
    <tr>
    	<td>Contact Person:</td>
        <td><input type="text" id="txtContactPerson" name="txtContactPerson" value="<?php if ( isset($_POST['txtContactPerson']) ) { echo $_POST['txtContactPerson']; } ?>" /></td>
    </tr>
    <tr>
    	<td>Mobile Phone:</td>
        <td><input type="text" id="txtMobilePhone" name="txtMobilePhone" value="<?php if ( isset($_POST['txtMobilePhone']) ) { echo $_POST['txtMobilePhone']; } ?>" /></td>
    </tr>
    <tr>
    	<td>Office Phone:</td>
        <td><input type="text" id="txtOfficePhone" name="txtOfficePhone" value="<?php if ( isset($_POST['txtOfficePhone']) ) { echo $_POST['txtOfficePhone']; } ?>" /></td>
    </tr>
    <tr>
    	<td>Home Phone:</td>
        <td><input type="text" id="txtHomePhone" name="txtHomePhone" value="<?php if ( isset($_POST['txtHomePhone']) ) { echo $_POST['txtHomePhone']; } ?>" /></td>
    </tr>
	<tr>
    	<td colspan="2">
        	<input type="submit" name="btnSave" id="btnSave" value="Save" />
        </td>
    </tr>
</table>

<?php
	echo $errMessage;
?>
</form>
</body>
</html>