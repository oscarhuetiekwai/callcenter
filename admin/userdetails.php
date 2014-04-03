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
	require_once('includes/classusers.php');
	require_once('includes/classuser.php');
	require_once('includes/classpagecommon.php');
	
	$pageCommon = new PageCommon();
	$users = new Users();
	$errMessage = '';
	$transactionType = 'add';
	$userID = 0;
	$curUser = NULL;
	
	if ( isset($_GET['userid']) ) {
		$userID = $_GET['userid'];
	} else if ( isset( $_POST['hdnUserID'] ) ) {
		$userID = $_POST['hdnUserID'];
	}

	if ( isset($_GET['type'] ) ) {
		$transactionType = $_GET['type'];
	} else if ( isset($_POST['hdnType'] ) ) {
		$transactionType = $_POST['hdnType'];
	}
	
	if ( isset( $_POST['btnSave'] ) ){
		$user = new User( $userID, $_POST['selTenants'],'',$_POST['txtUsername'],$_POST['txtUserpass'],$_POST['selUserLevel'],
			$_POST['txtLastname'], $_POST['txtFirstname'] );
	
		if ( $users->saveUser( $user ) ) {
			header('Location:adminusers.php');
		} else {
			$errMessage =  '<p>' . $users->getLastErrorMessage() . '</p>';
		}
		
	} else {
		if ( $userID > 0 ) {
			$curUser = $users->getUserInfo($userID);
			
			$_POST['selTenants'] = $curUser->getTenantID();
			$_POST['txtUsername'] = $curUser->getUsername();
			$_POST['txtUserpass'] = $curUser->getUserpass();
			$_POST['selUserLevel'] = $curUser->getUserLevel();
			$_POST['txtLastname'] = $curUser->getLastname();
			$_POST['txtFirstname'] = $curUser->getFirstname();
		}
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>User Details</title>
<link rel="stylesheet" type="text/css" href="css/common.css" />
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepalive.js"></script>
</head>

<body onload="startKeepAlive();"  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']); 
	$pageCommon->displayNavigation('admin', 'users');
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="post" name="frmExtension">
<input type="hidden" name="hdnType" id="hdnType" value="<?php echo $transactionType;  ?>" />
<input type="hidden" name="hdnUserID" id="hdnUserID" value="<?php echo $userID;  ?>" />
<?php 
if ( $_SESSION['WEB_ADMIN_TENANTID'] != "0" ) {
	echo '<input type="hidden" name="selTenants" id="selTenants" value="' . $_SESSION['WEB_ADMIN_TENANTID'] .
		'" />';
}
?>

<table width="100%" height="433" border="0" cellspacing="0" cellpadding="0">
	<tr>
    	<td width="50">&nbsp;</td>
    	<td valign="top">
        	<br />
	    	<table width="600" border="0" cellspacing="2" cellpadding="2">
            	<tr>
	    			<td colspan="2" class="tableHeader">
<?php
    
	if ( $transactionType == 'add' ) {
		echo 'Add New User';
	} else if ($transactionType == 'edit' ) {
		echo 'Edit user "' . $_POST['txtUsername'] . '"';
	} else if ($transactionType == 'delete' ) {
		echo 'Are you sure you want to delete user "' . $_POST['txtUsername'] . '"?';
	}
	
?>
                    </td>
                </tr>
<?php
    
		if ( $_SESSION['WEB_ADMIN_TENANTID'] == "0" ) {
	    	echo '<tr><td class="tableRow">Tenants:</td>';
	        echo '<td class="tableRow"> ';
       		echo '<select id="selTenants" name="selTenants" width="200" style="width: 200px" size="0">';
			if ( $transactionType == 'add' ) {
				$users->getAllTenantsOption(0); 
			} else {
				$users->getAllTenantsOption($_POST['selTenants']); 
			}
			echo '</select>';
	        echo '</td></tr>';
		} 
    ?>                
               <tr>
                    <td class="tableRow" width="150">Username:</td>
                    <td class="tableRow"><input type="text" id="txtUsername" name="txtUsername" width="200" style="width: 200px" value="<?php if ( isset($_POST['txtUsername']) ) { echo $_POST['txtUsername']; } ?>" /></td>
                </tr>
                <tr>
                    <td class="tableRow">Password:</td>
                    <td class="tableRow"><input type="text" id="txtUserpass" name="txtUserpass" width="200" style="width: 200px" value="<?php if ( isset($_POST['txtUserpass']) ) { echo $_POST['txtUserpass']; } ?>" /></td>
                </tr>
                <tr>
                    <td class="tableRow">Last Name:</td>
                    <td class="tableRow"><input type="text" id="txtLastname" name="txtLastname" width="200" style="width: 200px" value="<?php if ( isset($_POST['txtLastname']) ) { echo $_POST['txtLastname']; } ?>" /></td>
                </tr>
                <tr>
                    <td class="tableRow">First Name:</td>
                    <td class="tableRow"><input type="text" id="txtFirstname" name="txtFirstname" width="200" style="width: 200px" value="<?php if ( isset($_POST['txtFirstname']) ) { echo $_POST['txtFirstname']; } ?>" /></td>
                </tr>
                <tr>
                    <td class="tableRow">User level:</td>
                    <td class="tableRow">
                        <select id="selUserLevel" name="selUserLevel" width="200" style="width: 200px" size="0">
                            <?php 
                                if ( $transactionType == 'add' ) {
                                    $users->getAllUsersLevelsOption($_SESSION['WEB_ADMIN_TENANTID'], 0); 
                                } else {
                                    $users->getAllUsersLevelsOption($_SESSION['WEB_ADMIN_TENANTID'], $_POST['selUserLevel']); 
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                	<td class="tableRow">Skills:</td>
                    <td class="tableRow"></td>
                </tr>
                <tr>
                    <td colspan="2" class="tableFooter" align="center">
                        <input type="submit" name="btnSave" id="btnSave" value="Save" />
                    </td>
                </tr>
         </table>
       </td>
    </tr>
</table>


<?php
	echo $errMessage;
?>
</form>
</body>
</html>