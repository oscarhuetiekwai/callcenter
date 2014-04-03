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
	
	if ( !isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		header("Location:login.php");
	}
	
	$pageCommon = new PageCommon();
	$tenants = new Tenants();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tenants</title>
<link rel="stylesheet" type="text/css" href="css/common.css" />
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepalivetenant.js"></script>
<script language="javascript" type="text/javascript">
	function onTenantClick(tenantID) {
		addHttpRequest( "admintenant:tenantinfo", "tenantid=" + tenantID );
	}
	
	function onBtnNewClick() {
		var hdnTenantID = document.getElementById("hdnTenantID");
		var txttenant = document.getElementById("txttenant");
		var txtcontactperson = document.getElementById("txtcontactperson");
		var txtofficephone = document.getElementById("txtofficephone");
		var txthomephone = document.getElementById("txthomephone");
		var txtmobileno = document.getElementById("txtmobileno");
		
		if ( hdnTenantID ) hdnTenantID.value = "0";
		if ( txttenant ) {
			txttenant.value = "";
			txttenant.focus();
		}
		if ( txtcontactperson ) txtcontactperson.value = "";
		if ( txtofficephone ) txtofficephone.value = "";
		if ( txthomephone ) txthomephone.value = "";
		if ( txtmobileno ) txtmobileno.value = "";
		
		document.getElementById("admintenanttitle").innerHTML = "New Tenant";
	}
	
	function onBtnSaveClick() {
		var hdnTenantID = document.getElementById("hdnTenantID");
		var txttenant = document.getElementById("txttenant");
		var txtcontactperson = document.getElementById("txtcontactperson");
		var txtofficephone = document.getElementById("txtofficephone");
		var txthomephone = document.getElementById("txthomephone");
		var txtmobileno = document.getElementById("txtmobileno");
		
		addHttpRequest( "admintenant:tenantsave", "tenantid=" + hdnTenantID.value + "&tenant=" + txttenant.value +
					   "&contactperson=" + txtcontactperson.value + "&officeno=" + txtofficephone.value +
					   "&homeno=" + txthomephone.value + "&mobileno=" + txtmobileno.value );
	}
</script>
</head>

<body onload="startKeepAlive();" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']); 
	$pageCommon->displayNavigation('admin', 'tenants');
?>

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    	<td width="15">&nbsp;</td>
    	<td valign="top" width="270">
	    	<table width="250" border="0" cellspacing="2" cellpadding="2">
            	<tr>
	    			<td colspan="2" class="tableTitle"><h2>Tenants</h2></td>
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
                    	<div id="divtenantlist" style="overflow:auto;width:230px;height:350px;border:1px solid #336699;padding-left:5px">
                        	<?php echo $tenants->displayTenantsList(); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                	<td colspan="2" align="center">
                    	<input type="button" name="btnNew" id="btnNew" value="New Tenant" onclick="onBtnNewClick();" />&nbsp;
                        <!-- <input type="button" name="btnDelete" id="btnDelete" value="Delete Wrapup" /> -->
                    </td>
                </tr>
            </table>
        </td>
        <td valign="top">
        	<h2><div id="admintenanttitle">New Tenant</div></h2>
            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="frmTenant">
            	<input type="hidden" id="hdnTenantID" value="0" />
	        	<table cellpadding="0" cellspacing="0" border="0" >
                	<tr align="left">
                    	<td align="left" width="250px">Tenant<br /><input type="text" id="txttenant" style="width: 180px"/></td>
                    </tr>
                	<tr align="left">
                    	<td align="left" width="250px">Contact Person<br /><input type="text" id="txtcontactperson" style="width: 180px"/></td>
                    </tr>
                    <tr align="left">
                    	<td align="left" width="250px">Office Phone<br /><input type="text" id="txtofficephone" style="width: 180px"/></td>
                    </tr>
                    <tr align="left">
                    	<td align="left" width="250px">Home Phone<br /><input type="text" id="txthomephone" style="width: 180px"/></td>
                    </tr>
                    <tr align="left">
                    	<td align="left" width="250px">Mobile No<br /><input type="text" id="txtmobileno" style="width: 180px"/></td>
                    </tr>                  
                    <tr>
                    	<td ><!--<input type="submit" name="btnSave" id="btnSave" value="Update User"/>-->
                        	<br/>
                        	<hr />
                            <br />
                        	<input type="button" name="btnSave" id="btnSave" value="Save Tenant" onclick="onBtnSaveClick();"/>
                        </td>
                    </tr>
    	        </table>
            </form>
        </td>
    </tr>
</table>
</body>
</html>