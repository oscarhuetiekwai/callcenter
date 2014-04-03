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
	require_once('includes/classskills.php');
	require_once('includes/classskill.php');
	require_once('includes/classpagecommon.php');
	
	if ( !isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		header("Location:login.php");
	}
	
	$pageCommon = new PageCommon();
	$skills = new Skills();
	$nSkillCount = $skills->getSkillsCount($_SESSION['WEB_ADMIN_TENANTID']);
	$transactionType = 'btnNew';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>User Level</title>
<link rel="stylesheet" type="text/css" href="css/common.css" />
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepaliveuserlevel.js"></script>
<script language="javascript" type="text/javascript" src="javascript/prototype.js"></script>
<script language="javascript" type="text/javascript" src="javascript/effects.js"></script>
<script type="text/javascript">
        function onBtnSaveClick(userlevel) {
                var checked = false;
                var values = "";
		checkboxes = document.getElementsByName("chkViews");

                for(var i in checkboxes){
                    if(checkboxes[i].checked)
                    {
                        if(values=="" && checkboxes[i].value)
                            values = checkboxes[i].value + ":checked";
                        else if(values!="" && checkboxes[i].value)
                            values += "," + checkboxes[i].value + ":checked";

                        checked = true;
                    }
                    else
                    {
                        if(values=="" && checkboxes[i].value)
                            values = checkboxes[i].value + ":unchecked";
                        else if(values!="" && checkboxes[i].value)
                            values += "," + checkboxes[i].value + ":unchecked";
                    }
                }

                checkboxes = document.getElementsByName("chkQuality Monitoring");

                for(var i in checkboxes){
                    if(checkboxes[i].checked)
                    {
                        if(values=="" && checkboxes[i].value)
                            values = checkboxes[i].value + ":checked";
                        else if(values!="" && checkboxes[i].value)
                            values += "," + checkboxes[i].value + ":checked";

                        checked = true;
                    }
                    else
                    {
                        if(values=="" && checkboxes[i].value)
                            values = checkboxes[i].value + ":unchecked";
                        else if(values!="" && checkboxes[i].value)
                            values += "," + checkboxes[i].value + ":unchecked";
                    }
                }

                checkboxes = document.getElementsByName("chkAdministrations");

                for(var i in checkboxes){
                    if(checkboxes[i].checked)
                    {
                        if(values=="" && checkboxes[i].value)
                            values = checkboxes[i].value + ":checked";
                        else if(values!="" && checkboxes[i].value)
                            values += "," + checkboxes[i].value + ":checked";

                        checked = true;
                    }
                    else
                    {
                        if(values=="" && checkboxes[i].value)
                            values = checkboxes[i].value + ":unchecked";
                        else if(values!="" && checkboxes[i].value)
                            values += "," + checkboxes[i].value + ":unchecked";
                    }
                }

                checkboxes = document.getElementsByName("chkReports");

                for(var i in checkboxes){
                    if(checkboxes[i].checked)
                    {
                        if(values=="" && checkboxes[i].value)
                            values = checkboxes[i].value + ":checked";
                        else if(values!="" && checkboxes[i].value)
                            values += "," + checkboxes[i].value + ":checked";

                        checked = true;
                    }
                    else
                    {
                        if(values=="" && checkboxes[i].value)
                            values = checkboxes[i].value + ":unchecked";
                        else if(values!="" && checkboxes[i].value)
                            values += "," + checkboxes[i].value + ":unchecked";
                    }
                }

                if(checked == true)
                {
                    addHttpRequest("adminuserlevel:saveauthorityaccess", "userlevelid=" + userlevel + "&accessauthorityvalues=" + values);
                }
		else
                    alert("Please checked at least one")
		
	}
	
	function reloadSkillList() {
		var nTenantID = document.getElementById("hdnTenantID").value;
		
		addHttpRequest("adminskill:skilllist", "tenantid=" + nTenantID);
	}
	
	function onUserLevelClick(nUserLevelID) {
		addHttpRequest("adminuserlevel:authorityaccess", "userlevelid=" + nUserLevelID);
	}

        function checkBoxes(obj,objname)
        {
                checkboxes = document.getElementsByName(objname);
                for(var i in checkboxes)
                    checkboxes[i].checked = obj.checked;
        }
</script>
</head>

<body onload="startKeepAlive();" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']); 
	$pageCommon->displayNavigation('admin', 'userlevel');
?>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    	<td width="15">&nbsp;</td>
    	<td valign="top" width="270">
	    	<table width="250" border="0" cellspacing="2" cellpadding="2">
            	<tr>
	    			<td colspan="2" class="tableTitle"><h2>User Level</h2></td>
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
                    	<div id="divuserlevellist" class="labelline" name="divuserlevellist" style="overflow:auto;width:230px;height:300px;border:1px solid #336699;padding-left:5px">
                        <!--<b>Total number of skills: <?php /*?><?php echo $nSkillCount; ?><?php */?></b><br/><br/>
                    	  <b>List of Skills</b>-->
						
                        <label for="chkuserlevel1" onclick="onUserLevelClick(1)"><b><font color="#009999">Guest</font></b></label>
                        <label for="chkuserlevel2" onclick="onUserLevelClick(2);"><b><font color="#009999">Agent</font></b></label>
                        <label for="chkuserlevel3" onclick="onUserLevelClick(3);"><b><font color="#009999">Supervisor</font></b></label>
                        <label for="chkuserlevel4" onclick="onUserLevelClick(4);"><b><font color="#009999">Admin</font></b></label>
                        <label for="chkuserlevel5" onclick="onUserLevelClick(5);"><b><font color="#009999">Team Leader</font></b></label>
                        </div>                                             
                    </td>
                </tr>
                <!--<tr>
                	<td colspan="2" align="center">
                    	<input type="button" name="btnNew" id="btnNew" value="New User Level" onclick="onBtnNewClick();" />&nbsp;
                        <input type="button" name="btnDelete" id="btnDelete" value="Delete User Level" />
                    </td>
                </tr>-->
            </table>
        </td>
        <td valign="top">
       	  <h2 >Authority Access</h2>
          <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="frmUserLevel">
          <table cellpadding="0" cellspacing="0" border="0" >
                	
                    <tr>
                    	<td id="authorityAccessContent"><!--<input type="submit" name="btnSave" id="btnSave" value="Update User"/>-->
                            <?php
                                //authorityAccess(1);
                            ?>
                        </td>
                    </tr>
                    <tr>
                    	<td id="saveButton"><!--<input type="submit" name="btnSave" id="btnSave" value="Update User"/>-->
                        	
                        </td>
                    </tr>
    	        </table>
            </form>
        </td>
    </tr>
</table>
</body>
</html>