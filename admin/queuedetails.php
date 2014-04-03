<?php
    session_start();
	require_once('includes/config.php'); 
	require_once('includes/errhandler.php');
	require_once('includes/classqueues.php');
	require_once('includes/classqueue.php');
	require_once('includes/classpagecommon.php');
	
	$pageCommon = new PageCommon();
	$queues = new Queues();
	$errMessage = '';
	$transactionType = 'add';
	$queueID = 0;
	$curQueue = NULL;
	$_POST['selTenants'] = $_SESSION['WEB_ADMIN_TENANTID'];
	
	if ( isset($_GET['queueid']) ) {
		$queueID = $_GET['queueid'];
	} else if ( isset( $_POST['hdnQueueID'] ) ) {
		$queueID = $_POST['hdnQueueID'];
	}

	if ( isset($_GET['type'] ) ) {
		$transactionType = $_GET['type'];
	} else if ( isset($_POST['hdnType'] ) ) {
		$transactionType = $_POST['hdnType'];
	}
	
	if ( isset( $_POST['btnSave'] ) ){
		$queue = new Queue( $queueID, $_POST['selTenants'], '',  $_POST['txtQueue'], $_POST['txtTimeout'],
			$_POST['txtServiceLevel'], $_POST['txtPriority'], $_POST['txtWrapupTime'] );
		
		for ( $nCnt = 1; $nCnt <= $queues->getAllTenantSkillsCount( $_POST['selTenants'] ); $nCnt++ ) {
			if ( isset($_POST['chk' . $nCnt]) ) {
				$tempSkillID = $_POST['chk' . $nCnt];
				$queue->addSkill($tempSkillID);
			}
		}
							
		if ( $queues->saveQueue( $queue ) ) {
			header('Location:adminqueues.php');
		} else {
			$errMessage =  '<p>' . $queues->getLastErrorMessage() . '</p>';
		}
		
	} else {
		$_POST['txtTimeout'] = 0;
		$_POST['txtServiceLevel'] = 20;
		$_POST['txtPriority'] = 0;
		$_POST['txtWrapupTime'] = 0;			
		
		if ( $queueID > 0 ) {
			$curQueue = $queues->getQueueInfo($queueID);
			$_POST['selTenants'] = $curQueue->getTenantID();
			$_POST['txtQueue'] = $curQueue->getQueueName();
			$_POST['txtTimeout'] = $curQueue->getTimeout();
			$_POST['txtServiceLevel'] = $curQueue->getServiceLevel();
			$_POST['txtPriority'] = $curQueue->getPriority();
			$_POST['txtWrapupTime'] = $curQueue->getWrapupTime();
		}
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Queue Details</title>
<link rel="stylesheet" type="text/css" href="css/common.css" />
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepalive.js"></script>
</head>

<body onload="startKeepAlive();" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']); 
	$pageCommon->displayNavigation('admin', 'queues');
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="post" name="frmExtension">
<input type="hidden" name="hdnType" id="hdnType" value="<?php echo $transactionType;  ?>" />
<input type="hidden" name="hdnQueueID" id="hdnQueueID" value="<?php echo $queueID;  ?>" />
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
		echo 'Add New Queue';
	} else if ($transactionType == 'edit' ) {
		echo 'Edit Queue "' . $_POST['txtQueue'] . '"';
	} else if ($transactionType == 'delete' ) {
		echo 'Are you sure you want to delete queue "' . $_POST['txtQueue'] . '"?';
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
				$queues->getAllTenantsOption(0); 
			} else {
				$queues->getAllTenantsOption($_POST['selTenants']); 
			}
			echo '</select>';
	        echo '</td></tr>';
		} 
    ?>                
               <tr>
                   <td class="tableRow" width="100">Queue:</td>
                   <td class="tableRow"><input type="text" id="txtQueue" name="txtQueue" width="200" style="width: 200px" value="<?php if ( isset($_POST['txtQueue']) ) { echo $_POST['txtQueue']; } ?>" /></td>
               </tr>
               <tr>
                   <td class="tableRow">Timeout:</td>
                   <td class="tableRow"><input class="txtRightAlign" type="text" id="txtTimeout" name="txtTimeout" width="200" style="width: 200px" value="<?php if ( isset($_POST['txtTimeout']) ) { echo $_POST['txtTimeout']; } ?>" />sec(s)</td>
               </tr>
               <tr>
                   <td class="tableRow">Service Level:</td>
                   <td class="tableRow"><input class="txtRightAlign" type="text" id="txtServiceLevel" name="txtServiceLevel" width="200" style="width: 200px"  value="<?php if ( isset($_POST['txtServiceLevel']) ) { echo $_POST['txtServiceLevel']; } ?>" />sec(s)</td>
               </tr>
               <tr>
                   <td class="tableRow">Priority:</td>
                   <td class="tableRow"><input class="txtRightAlign" type="text" id="txtPriority" name="txtPriority" width="200" style="width: 200px" value="<?php if ( isset($_POST['txtPriority']) ) { echo $_POST['txtPriority']; } ?>" /></td>
               </tr>
               <tr>
                   <td class="tableRow">Wrapup Time:</td>
                   <td class="tableRow"><input class="txtRightAlign" type="text" id="txtWrapupTime" name="txtWrapupTime" width="200" style="width: 200px" value="<?php if ( isset($_POST['txtWrapupTime']) ) { echo $_POST['txtWrapupTime']; } ?>" />sec(s)</td>
               </tr>            
               <tr>
                   <td class="tableRow">Skills:</td>
                   <td class="tableRow">
<!--                       <select name="lstSkills" id="lstSkills" size="5" multiple="multiple" width="200" style="width: 200px"> -->
                           <?php 
						   		if ( $curQueue != NULL ) {
							   		$queues->getAllTenantSkillsOption($_POST['selTenants'], $curQueue->getSkills()); 
								} else {
									$queues->getAllTenantSkillsOption($_POST['selTenants'], array()); 
								}
						   ?> 
<!--                       </select> -->
                   </td>
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