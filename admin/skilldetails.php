<?php
    session_start();
	require_once('includes/config.php'); 
	require_once('includes/errhandler.php');
	require_once('includes/classskills.php');
	require_once('includes/classskill.php');
	require_once('includes/classpagecommon.php');
	
	$pageCommon = new PageCommon();
	$skills = new Skills();
	$errMessage = '';
	$transactionType = 'add';
	$skillID = 0;
	$curSkill = NULL;
	
	if ( isset($_GET['skillid']) ) {
		$skillID = $_GET['skillid'];
	} else if ( isset( $_POST['hdnSkillID'] ) ) {
		$skillID = $_POST['hdnSkillID'];
	}

	if ( isset($_GET['type'] ) ) {
		$transactionType = $_GET['type'];
	} else if ( isset($_POST['hdnType'] ) ) {
		$transactionType = $_POST['hdnType'];
	}
	
	if ( isset( $_POST['btnSave'] ) ){
		$skill = new Skill( $skillID, $_POST['selTenants'], $_POST['selTenants'], $_POST['txtSkill'], 
				$_POST['txtDesc']);
			
		if ( $skills->saveSkill( $skill ) ) {
			header('Location:adminskills.php');
		} else {
			$errMessage =  '<p>' . $skills->getLastErrorMessage() . '</p>';
		}
		
	} else {
		if ( $skillID > 0 ) {
			$curSkill = $skills->getSkillInfo($skillID);
			$_POST['selTenants'] = $curSkill->getTenantID();
			$_POST['txtSkill'] = $curSkill->getSkill();
			$_POST['txtDesc'] = $curSkill->getSkillDesc();
		}
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Skill Details</title>
<link rel="stylesheet" type="text/css" href="css/common.css" />
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepalive.js"></script>
</head>

<body onload="startKeepAlive();"  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']); 
	$pageCommon->displayNavigation('admin', 'skills');
?><br />

<form action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="post" name="frmExtension">
<input type="hidden" name="hdnType" id="hdnType" value="<?php echo $transactionType;  ?>" />
<input type="hidden" name="hdnSkillID" id="hdnSkillID" value="<?php echo $skillID;  ?>" />

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
		echo 'Add New Skill';
	} else if ($transactionType == 'edit' ) {
		echo 'Edit skill "' . $_POST['txtSkill'] . '"';
	} else if ($transactionType == 'delete' ) {
		echo 'Are you sure you want to delete skill "' . $_POST['txtSkill'] . '"?';
	}
	
?>
                    </td>
                </tr>
	<?php
    
		if ( $_SESSION['WEB_ADMIN_TENANTID'] == "0" ) {
	    	echo '<tr><td class="tableRow" >Tenants:</td>';
	        echo '<td class="tableRow" > ';
       		echo '<select id="selTenants" name="selTenants" width="200" style="width: 200px" size="0">';
			if ( $transactionType == 'add' ) {
				$skills->getAllTenantsOption(0); 
			} else {
				$skills->getAllTenantsOption($_POST['selTenants']); 
			}
			echo '</select>';
	        echo '</td></tr>';
		} 
    ?>                
               <tr>
                   <td class="tableRow" width="150">Skill:</td>
                   <td class="tableRow"><input type="text" id="txtSkill" name="txtSkill" width="200" style="width: 200px" value="<?php if ( isset($_POST['txtSkill']) ) { echo $_POST['txtSkill']; } ?>" /></td>
               </tr>
               <tr>
                   <td class="tableRow">Description:</td>
                   <td class="tableRow"><input type="text" id="txtDesc" name="txtDesc" width="200" style="width: 200px" value="<?php if ( isset($_POST['txtDesc']) ) { echo $_POST['txtDesc']; } ?>" /></td>
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