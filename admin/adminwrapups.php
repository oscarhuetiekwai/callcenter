
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
	require_once('includes/classwrapups.php');
	require_once('includes/classwrapup.php');
	require_once('includes/classpagecommon.php');
	
	if ( !isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		header("Location:login.php");
	}
	
	$pageCommon = new PageCommon();
	$wrapups = new Wrapups();

    $nWrapupCount = $wrapups->getWrapupCount( $_SESSION['WEB_ADMIN_TENANTID'] );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Wrapups</title>
<link rel="stylesheet" type="text/css" href="javascript/jqueryui/css/redmond/jquery-ui-1.8.4.custom.css" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />

<script src="javascript/jquery-1.9.1.js"></script>
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepalivewrapup.js"></script>
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
<script language="javascript" type="text/javascript">

	function onBtnSaveClick() {
		var nWrapupID = document.getElementById("hdnWrapupID").value;
		var nTenantID = document.getElementById("hdnTenantID").value;
		var szWrapup = document.getElementById("txtwrapup").value;
		var szDescription = document.getElementById("txtdescription").value;
		
		addHttpRequest("adminwrapup:savewrapup", "wrapupid=" + nWrapupID + "&tenantid=" + nTenantID + "&wrapup=" + szWrapup + "&desc=" + szDescription);
		
	}

        function onBtnDltClick() {
                var values = "";

                for (i = 0; i < document.getElementsByName("chkwrapup").length; i++) {
                    if(document.getElementsByName("chkwrapup").item(i).checked)
                    {
                        if(values == "")
                        {
                            values += document.getElementsByName("chkwrapup").item(i).value;
                        }
                        else
                        {
                            values += ";" + document.getElementsByName("chkwrapup").item(i).value;
                        }
                    }
                }

                if(values != "")
                {
                    var agree=confirm("ARE YOU SURE ?");

                    if (agree)
                    {
                        addHttpRequest("adminwrapup:wrapupdelete", "wrapups=" + values);
                    }
                }
                else
                {
                    alert("Please check Wrapup")
                }
        }
	
	function reloadWrapupList() {
		var nTenantID = document.getElementById("hdnTenantID").value;
		
		addHttpRequest("adminwrapup:wrapuplist", "tenantid=" + nTenantID);
	}
	
	function onWrapupClick(nWrapupID) {
		addHttpRequest("adminwrapup:wrapupinfo", "wrapupid=" + nWrapupID);
	}
	
	
	function onBtnNewClick() {
		document.getElementById("hdnWrapupID").value = "0";
		document.getElementById("txtwrapup").value = "";
		document.getElementById("txtdescription").value = "";
		document.getElementById("adminwrapuptitle").innerHTML = "Add New Wrapup";
		document.getElementById("btnSave").value ="Save Wrapup";
		document.getElementById("txtwrapup").focus();
	}
	
</script>
</head>

<body onload="startKeepAlive();" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']); 
	$pageCommon->displayNavigation('admin', 'wrapups');
?>

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    	<td width="15">&nbsp;</td>
    	<td valign="top" width="270">
	    	<table width="250" border="0" cellspacing="2" cellpadding="2">
            	<tr>
	    			<td colspan="2" class="tableTitle"><h2>Wrapups</h2></td>
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
                    	<div class="labelline">
                    	<div id="divwrapuplist" name="divwrapuplist" style="overflow:auto;width:230px;height:370px;border:1px solid #336699;padding-left:5px">
                        	<?php echo $wrapups->displayWrapupList($_SESSION['WEB_ADMIN_TENANTID']); ?>
                        </div>
                        </div>
                    </td>
                </tr>
                <tr>
                	<td colspan="2" align="center">
                    	<input type="button" name="btnNew" id="btnNew" value="New Wrapup" onclick="onBtnNewClick();" />&nbsp;
                        <input type="button" name="btnDelete" id="btnDelete" value="Delete Wrapup" onclick="onBtnDltClick();" />
                    </td>
                </tr>
            </table>
        </td>
        <td valign="top">
        	<h2><div id="adminwrapuptitle" name="adminwrapuptitle">Add New Wrapup</div></h2>
            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="frmWrapup">
            	<input type="hidden" id="hdnTenantID" name="hdnTenantID" value="<?php echo $_SESSION['WEB_ADMIN_TENANTID']; ?>" />
            	<input type="hidden" id="hdnWrapupID" name="hdnWrapupID" value="0" />
	        	<table cellpadding="0" cellspacing="0" border="0" >
                	<tr align="left">
                    	<td align="left" width="250px">Wrapup<br /><input type="text" id="txtwrapup" name="txtwrapup" width="180" style="width: 180px"/></td>
                    </tr>
                	<tr align="left">
                    	<td align="left">Description<br />
                        	<textarea id="txtdescription" name="txtdescription" height="100" width="350" style="width: 350px; height: 100px;" ></textarea>
                        </td>
                    </tr>                    
                    <tr>
                    	<td ><!--<input type="submit" name="btnSave" id="btnSave" value="Update User"/>-->
                        	<br/>
                        	<hr />
                            <br />
                        	<input type="button" name="btnSave" id="btnSave" value="Save Wrapup" onclick="onBtnSaveClick();"/>
                        </td>
                    </tr>
    	        </table>
            </form>
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