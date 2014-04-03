<?php
    session_start();
	require_once('includes/config.php'); 
	require_once('includes/errhandler.php');
	require_once('includes/classextensions.php');
	require_once('includes/classextension.php');
	require_once('includes/classpagecommon.php');
	
	$pageCommon = new PageCommon();
	$extensions = new Extensions();
	$errMessage = '';
	$transactionType = 'add';
	$extensionID = 0;
	$curExtension = NULL;
	
	if ( isset($_GET['extenid']) ) {
		$extensionID = $_GET['extenid'];
	} else if ( isset( $_POST['hdnExtenID'] ) ) {
		$extensionID = $_POST['hdnExtenID'];
	}

	if ( isset($_GET['type'] ) ) {
		$transactionType = $_GET['type'];
	} else if ( isset($_POST['hdnType'] ) ) {
		$transactionType = $_POST['hdnType'];
	}
	
	if ( isset( $_POST['btnSave'] ) ){
	
		$extension = new Extension( $extensionID, $_POST['selTenants'], $extensions->getTenantName($_POST['selTenants']), 
			$_POST['txtName'], $_POST['txtCallerID'], $_POST['txtSecret'] );
		
		if ( $extensions->saveExtension( $extension ) ) {
			header('Location:extension.php');
		} else {
			$errMessage =  '<p>' . $extensions->getLastErrorMessage() . '</p>';
		}
		
	} else {
		if ( $extensionID > 0 ) {
			$curExtension = $extensions->getExtensionInfo($extensionID);
			
			$_POST['txtName'] = $curExtension->getExtensionName();
			$_POST['txtCallerID'] = $curExtension->getCallerID();
			$_POST['txtSecret'] = $curExtension->getSecret();
			$_POST['selTenants'] = $curExtension->getTenantID();
		}
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Extension Details</title>
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
		echo 'Add New Extension';
	} else if ($transactionType == 'edit' ) {
		echo 'Edit Extension "' . $_POST['txtName'] . '"';
	} else if ($transactionType == 'delete' ) {
		echo 'Are you sure you want to delete extension "' . $_POST['txtName'] . '"?';
	}
	
?>
</h2>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="post" name="frmExtension">
<input type="hidden" name="hdnType" id="hdnType" value="<?php echo $transactionType;  ?>" />
<input type="hidden" name="hdnExtenID" id="hdnExtenID" value="<?php echo $extensionID;  ?>" />
<table>
	<tr>
    	<td>Tenants:</td>
        <td>
        	<select id="selTenants" name="selTenants">
            	<?php 
					if ( $transactionType == 'add' ) {
						$extensions->getAllTenantsOption(0); 
					} else {
						$extensions->getAllTenantsOption($curExtension->getTenantID()); 
					}
				?>
            </select>
        </td>
    </tr>
    <tr>
    	<td>Name:</td>
        <td><input type="text" id="txtName" name="txtName" value="<?php if ( isset($_POST['txtName']) ) { echo $_POST['txtName']; } ?>" /></td>
    </tr>
    <tr>
    	<td>Caller ID:</td>
        <td><input type="text" id="txtCallerID" name="txtCallerID" value="<?php if ( isset($_POST['txtCallerID']) ) { echo $_POST['txtCallerID']; } ?>" /></td>
    </tr>
    <tr>
    	<td>Secret:</td>
        <td><input type="text" id="txtSecret" name="txtSecret" value="<?php if ( isset($_POST['txtSecret']) ) { echo $_POST['txtSecret']; } ?>" /></td>
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