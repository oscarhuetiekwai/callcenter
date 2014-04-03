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
	require_once('includes/classunavailcode.php');
	require_once('includes/classunavailcodes.php');
	require_once('includes/classpagecommon.php');
	
	if ( !isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		header("Location:login.php");
	}
	
	$pageCommon = new PageCommon();
	
	$unavails = new UnavailCodes();
	$transactionType = 'btnNew';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Unavailable Codes</title>

<link rel="stylesheet" type="text/css" href="javascript/jqueryui/css/redmond/jquery-ui-1.8.4.custom.css" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />

<script src="javascript/jquery-1.9.1.js"></script>
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepaliveunavail.js"></script>
<script language="javascript" type="text/javascript" src="javascript/jqueryui/js/jquery-1.4.2.min.js"></script>
<script language="javascript" type="text/javascript" src="javascript/jqueryui/js/jquery-ui-1.8.4.custom.min.js"></script>
<script type="text/javascript" src="javascript/jquery.ui.core.js" ></script>
<script type="text/javascript" src="javascript/jquery.blockUI.js" ></script>
<script type="text/javascript" src="javascript/jquery.DOMwindow.js" ></script>
<script type="text/javascript" src="javascript/jquery.selectboxes.js" ></script>
<script type="text/javascript" src="javascript/jquery.datepick.js" ></script>
<script type="text/javascript" src="javascript/jquery.autocomplete.js" ></script>
<script type="text/javascript" src="javascript/jquery.autocomplete.min.js" ></script>

<?php $pageCommon->displayScriptChangePwd(); ?>

<script type="text/javascript">

        function onBtnSaveClick() {
		/*var nSkillID = document.getElementById("hdnSkillID").value;
		var nTenantID = document.getElementById("hdnTenantID").value;
		var szTenant = document.getElementById("hdnTenant").value;
		var szSkill = document.getElementById("txtSkill").value;
		var szDesc = document.getElementById("txtDesc").value;
				
		addHttpRequest("adminskill:saveskill", "skillid=" + nSkillID + "&tenantid=" + nTenantID + "&tenant=" + szTenant + "&skill=" + szSkill + "&desc=" + szDesc); */
		
	}

        function onBtnDltClick() {
                var values = "";

                for (i = 0; i < document.getElementsByName("chkunavail").length; i++) {
                    if(document.getElementsByName("chkunavail").item(i).checked)
                    {
                        if(values == "")
                        {
                            values += document.getElementsByName("chkunavail").item(i).value;
                        }
                        else
                        {
                            values += ";" + document.getElementsByName("chkunavail").item(i).value;
                        }
                    }
                }

                if(values != "")
                {
                    var agree=confirm("ARE YOU SURE ?");

                    if (agree)
                    {
                        addHttpRequest("adminunavail:unavaildelete", "unavails=" + values);
                    }
                }
                else
                {
                    alert("Please check Unavailable Code")
                }
        }
	
	function reloadUnavailCodeList() {/*
		var nTenantID = document.getElementById("hdnTenantID").value;
		
		addHttpRequest("adminskill:unavaillist", "tenantid=" + nTenantID);*/
	}
	
	function onFilterChange() {
		var selFilter = document.getElementById("selFilter");
		
		if ( selFilter ) {
			addHttpRequest( "adminunavail:unavaillist", "tenantid=" + document.getElementById("hdnTenantID").value + 
							"&filter=" + selFilter.value);
		}
	}
	
	function onUnavailCodeClick(nUserStatusID) {
		if ( nUserStatusID < 100 ) {
			alert( "You not allowed to modified system unavailable codes!!!" );
		} else {
			addHttpRequest("adminunavail:unavailinfo", "userstatusid=" + nUserStatusID );
		}
	}
	
	
	function onBtnNewClick() {
		document.getElementById("adminskilltitle").innerHTML = "Add New Unavailable Codes";
		document.getElementById("btnSave").value = "Save Code";
		document.getElementById("hdnUserStatusID").value = "0";
		document.getElementById("txtUnavail").value = "";
		document.getElementById("selproductive").value = 0;
	}


	function onBtnSaveClick() {
		var nUserStatusID = document.getElementById("hdnUserStatusID").value;
		var nTenantID = document.getElementById("hdnTenantID").value;
		var szUnavail = document.getElementById("txtUnavail").value;
		var nProductive = document.getElementById("selproductive").value;
		
		if ( szUnavail == "" ) {
			alert("Please input the unavailable code!");
			document.getElementById("txtUnavail").focus();
		} else {
			addHttpRequest("adminunavail:unavailsave", "userstatusid=" + nUserStatusID + "&userstatus=" +
						   szUnavail + "&tenantid=" + nTenantID + "&productive=" + nProductive);
		}
	}
</script>
</head>

<body onload="startKeepAlive();" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']); 
	$pageCommon->displayNavigation('admin', 'unavailcodes');
?>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    	<td width="15">&nbsp;</td>
    	<td valign="top" width="270">
	    	<table width="250" border="0" cellspacing="2" cellpadding="2">
            	<tr>
	    			<td colspan="2" class="tableTitle"><h2>Unavailable Codes</h2></td>
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
                	<td>Filter By:</td>
                    <td>
                            <select id="selFilter" name="selFilter" width="180" style="width: 180px" onchange="onFilterChange();">
                            	<option value="0" selected="selected">All</option>
                                <option value="1">System</option>
                                <option value="2">Productive</option>
                                <option value="3">Unproductive</option>
                            </select>                    
                    </td>
                </tr>
                <tr>
                	<td colspan="2">
                    	<div class="labelline">
                    	<div id="divunavallist" style="overflow:auto;width:230px;height:300px;border:1px solid #336699;padding-left:5px">
                        	<?php echo $unavails->displayUnavailableCodes($_SESSION['WEB_ADMIN_TENANTID'], 0); ?>
                        </div>                 
                        </div>                            
                    </td>
                </tr>
                <tr>
                	<td colspan="2" align="center">
                    	<input type="button" name="btnNew" id="btnNew" value="New Code" onclick="onBtnNewClick();" />&nbsp;
                        <input type="button" name="btnDelete" id="btnDelete" value="Delete Code" onclick="onBtnDltClick();" />
                    </td>
                </tr>
            </table>
        </td>
        <td valign="top">
       	  <h2 ><div id="adminskilltitle" name="adminskilltitle">Add New Unavailable Codes</div></h2>
          <input type="hidden" id="hdnTenantID" value="<?php echo $_SESSION['WEB_ADMIN_TENANTID']; ?>" />
            	<input type="hidden" id="hdnUserStatusID" value="0" />
                
          		<table cellpadding="0" cellspacing="0" border="0" >
                	<tr align="left">
                    	<td align="left" width="250px">&nbsp;<br />
               
                      <input type="hidden" id="hdnTenant" name="hdnTenant" width="180" style="width: 180px"/></td>
                    </tr>
                	<tr align="left">
                    	<td align="left">Unavailable Code<br />
                        	<input type="text" id="txtUnavail" name="txtUnavail" width="180" style="width: 180px"/>
                        </td>
                    </tr> 
                    <tr align="left">
                    	<td align="left">Productive<br />
                        	<select id="selproductive" style="width: 180px">
                            	<option value="0">no</option>
                                <option value="1">yes</option>
                            </select>
                        </td>
                    </tr>                                   
                    <tr>
                    	<td ><!--<input type="submit" name="btnSave" id="btnSave" value="Update User"/>-->
                        	<br/>
                        	<hr />
                            <br />
                            <input type="button" name="btnSave" id="btnSave" value="Save Code" onclick="onBtnSaveClick();"/>
                        </td>
                    </tr>
    	        </table>
        </td>
    </tr>
</table>
<?php $pageCommon->displayDivChangePwd(); ?>

<!-- start chat -->
<?php include("chat/chat.php"); ?>
<!-- end chat -->
<!-- End:   DIV Windows -->
</body>
</html>
<?php include("chat/javascript.php"); ?>