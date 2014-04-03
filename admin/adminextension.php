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
	$transactionType = 'add';
//	$extensionID = 0;
//	$curExtension = NULL;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Extensions</title>
<link rel="stylesheet" type="text/css" href="css/common.css" />
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepaliveextension.js"></script>
<script language="javascript" type="text/javascript" src="javascript/prototype.js"></script>
<script language="javascript" type="text/javascript" src="javascript/effects.js"></script>
<script language="javascript" type="text/javascript">

	function onBtnSaveClick() {
		var nExtensionID = document.getElementById("hdnExtensionID").value;
		var nTenantID = document.getElementById("hdnTenantID").value;
		var szTenantName = document.getElementById("hdnTenantName").value;
		var szExtensionName = document.getElementById("txtExtensionName").value;
		var szCallerID = document.getElementById("txtCallerId").value;
		var szSecret = document.getElementById("txtSecret").value;
		
		addHttpRequest("adminextension:saveextension", "extensionid=" + nExtensionID + "&tenantid=" + nTenantID + "&tenantname=" + szTenantName + "&extensionname=" + szExtensionName + "&callerid=" + szCallerID + "&secret=" + szSecret);
		
	}
	
	function reloadExtensionList() {
		var nTenantID = document.getElementById("hdnTenantID").value;
		
		addHttpRequest("adminextension:extensionlist", "tenantid=" + nTenantID);
	}
	
	function onExtensionClick(nExtensionID) {
		addHttpRequest("adminextension:extensioninfo", "extensionid=" + nExtensionID);
	}
	
	
	function onBtnNewClick() {
		document.getElementById("hdnExtensionID").value = "0";
		
		document.getElementById("hdnTenantName").value = "";
		document.getElementById("txtExtensionName").value = "";
		document.getElementById("txtCallerId").value = "";
		document.getElementById("txtSecret").value = "";
		
		document.getElementById("adminextensiontitle").innerHTML = "Add New Extension";
		document.getElementById("btnSave").value ="Save Extension";
		document.getElementById("txtextension").focus();
	}
	
/*function onUserLevelChange() {
		if ( document.getElementById("hdnTenantName").value == "2" )
			document.getElementById("hdnTenantID").style.visibility = "visible";
		else 
			document.getElementById("hdnTenantID").style.visibility = "hidden";
	}*/
</script>
</head>

<body onload="startKeepAlive();" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']); 
	$pageCommon->displayNavigation('admin', 'extensions');
?>

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    	<td width="15">&nbsp;</td>
    	<td valign="top" width="270">
	    	<table width="250" border="0" cellspacing="2" cellpadding="2">
            	<tr>
	    			<td colspan="2" class="tableTitle"><h2>Extensions</h2></td>
                </tr>
<?php
			if ( $_SESSION['WEB_ADMIN_TENANTID'] == "0" ) {
				echo '<tr>';
				echo '	<td>Tenant</td>';
				echo '  <td>';
				echo '  </td>';
			}
?>
				<!-- <tr>
                	<td>Filter By:</td>
                    <td>
                            <select id="selFilter" name="selFilter" width="180" style="width: 180px" onchange="onFilterChange(<?php echo $_SESSION['WEB_ADMIN_TENANTID']; ?>);">
                            	<option value="0" selected="selected">All</option>
                                <option value="1">Disabled</option>
                                <option value="2">Agent</option>
                                <option value="3">Supervisor</option>
                            </select>                    
                    </td>
                </tr> -->
                <tr>
                	<td colspan="2">
                    	<div id="divextensionlist" name="divextensionlist" style="overflow:auto;width:230px;height:300px;border:1px solid #336699;padding-left:5px">
                        <!--<b>Total number of extensions: <?php /*?><?php echo $nExtensionCount; ?><?php */?></b><br/><br/>-->
                    	 <!-- <b>List of Extensions</b>-->
						
                        <?php echo $extensions->displayExtensionsList($_SESSION['WEB_ADMIN_TENANTID']); ?>
                        </div>                                             
                    </td>
                </tr>
                <tr>
                	<td colspan="2" align="center">
                    	<input type="button" name="btnNew" id="btnNew" value="New Extension" onclick="onBtnNewClick();" />&nbsp;
                        <input type="button" name="btnDelete" id="btnDelete" value="Delete Extension" />
                    </td>
                </tr>
            </table>
        </td>
        <td valign="top">
       	  <h2 ><div id="adminextensiontitle" name="adminextensiontitle">Add New Extension</a></h2>
          <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="frmExtension">
          <input type="hidden" id="hdnTenantID" name="hdnTenantID" value="<?php echo $_SESSION['WEB_ADMIN_TENANTID']; ?>" />
            	<input type="hidden" id="hdnExtensionID" name="hdnExtensionID" value="0" />
                
          <table cellpadding="0" cellspacing="0" border="0" >
                	<tr align="left">
                    	<td align="left" width="250px">Tenant<br />
                       <?php /*?><select id="hdnTenantName" name="hdnTenantName" width="180" style="width: 180px"> &nbsp;<?php echo $extensions->getTenantList($_SESSION['WEB_ADMIN_TENANTID']); ?>
                            </select><?php */?>
                       	<select id="hdnTenantName" name="hdnTenantName" width="180" style="width: 180px">	
                         	<?php echo $extensions->getAllTenantsOption($_SESSION['WEB_ADMIN_TENANTID']); ?>
                        </select>		
                   	  <!--  <input type="text" id="hdnTenantName" name="hdnTenantName" width="180" style="width: 180px"/>--></td>
                    </tr>
                	<tr align="left">
                    	<td align="left">Extension Name<br />
                        	<input type="text" id="txtExtensionName" name="txtExtensionName" width="180" style="width: 180px"/>
                        </td>
                    </tr> 
                    <tr align="left">
                    	<td align="left">Caller ID<br />
                        	<input type="text" id="txtCallerId" name="txtCallerId" width="180" style="width: 180px"/>
                        </td>
                    </tr>  
                    <tr align="left">
                    	<td align="left">Secret<br />
                        	<input type="password" id="txtSecret" name="txtSecret" width="180" style="width: 180px"/>
                        </td>
                    </tr>                                   
                    <tr>
                    	<td ><!--<input type="submit" name="btnSave" id="btnSave" value="Update User"/>-->
                        	<br/>
                        	<hr />
                            <br />
                        	<input type="button" name="btnSave" id="btnSave" value="Save Extension" onclick="onBtnSaveClick();"/>
                        </td>
                    </tr>
    	        </table>
            </form>
        </td>
    </tr>
</table>
</body>
</html>