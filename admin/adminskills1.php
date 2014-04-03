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
<title>Skills</title>
<link rel="stylesheet" type="text/css" href="javascript/jqueryui/css/redmond/jquery-ui-1.8.4.custom.css" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />

<script src="javascript/jquery-1.9.1.js"></script>
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepaliveskill.js"></script>
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
		var nSkillID = document.getElementById("hdnSkillID").value;
		var nTenantID = document.getElementById("hdnTenantID").value;
		var szTenant = document.getElementById("hdnTenant").value;
		var szSkill = document.getElementById("txtSkill").value;
		var szDesc = document.getElementById("txtDesc").value;
				
		addHttpRequest("adminskill:saveskill", "skillid=" + nSkillID + "&tenantid=" + nTenantID + "&tenant=" + szTenant + "&skill=" + szSkill + "&desc=" + szDesc);
		
	}
        
        function onBtnDltClick() {
                var values = "";

                for (i = 0; i < document.getElementsByName("chkskill").length; i++) {
                    if(document.getElementsByName("chkskill").item(i).checked)
                    {
                        if(values == "")
                        {
                            values += document.getElementsByName("chkskill").item(i).value;
                        }
                        else
                        {
                            values += ";" + document.getElementsByName("chkskill").item(i).value;
                        }
                    }
                }

                if(values != "")
                {
                    var agree=confirm("ARE YOU SURE ?");

                    if (agree)
                    {
                        addHttpRequest("adminskill:skilldelete", "skills=" + values);
                    }
                }
                else
                {
                    alert("Please check Skill")
                }
        }
	
	function reloadSkillList() {
		var nTenantID = document.getElementById("hdnTenantID").value;
		
		addHttpRequest("adminskill:skilllist", "tenantid=" + nTenantID);
	}
	
	function onSkillClick(nSkillID) {
		addHttpRequest("adminskill:skillinfo", "skillid=" + nSkillID);
	}
	
	
	function onBtnNewClick() {
		document.getElementById("hdnSkillID").value = "0";
		document.getElementById("hdnTenant").value = "";
		document.getElementById("txtSkill").value = "";
		document.getElementById("txtDesc").value = "";
		
		document.getElementById("adminskilltitle").innerHTML = "Add New Skill";
		document.getElementById("btnSave").value ="Save Skill";
		document.getElementById("txtSkill").focus();
	}
	
</script>
</head>

<body onload="startKeepAlive();" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']); 
	$pageCommon->displayNavigation('admin', 'skills');
?>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    	<td width="15">&nbsp;</td>
    	<td valign="top" width="270">
	    	<table width="250" border="0" cellspacing="2" cellpadding="2">
            	<tr>
	    			<td colspan="2" class="tableTitle"><h2>Skills</h2></td>
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
                    	<div style="overflow:auto;width:230px;height:300px;border:1px solid #336699;padding-left:5px">
                        <!--<b>Total number of skills: <?php /*?><?php echo $nSkillCount; ?><?php */?></b><br/><br/>
                    	  <b>List of Skills</b>-->
						
                        <div id="divskilllist" name="divskilllist" class="labelline"><?php echo $skills->displaySkillList($_SESSION['WEB_ADMIN_TENANTID']); ?></div>
                        </div>                                             
                    </td>
                </tr>
                <tr>
                	<td colspan="2" align="center">
                    	<input type="button" name="btnNew" id="btnNew" value="New Skill" onclick="onBtnNewClick();" />&nbsp;
                        <input type="button" name="btnDelete" id="btnDelete" value="Delete Skill" onclick="onBtnDltClick();"/>
                    </td>
                </tr>
            </table>
        </td>
        <td valign="top">
       	  <h2 ><div id="adminskilltitle" name="adminskilltitle"><a>Add New Skill</a></h2>
          <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="frmSkill">
          <input type="hidden" id="hdnTenantID" name="hdnTenantID" value="<?php echo $_SESSION['WEB_ADMIN_TENANTID']; ?>" />
            	<input type="hidden" id="hdnSkillID" name="hdnSkillID" value="0" />
                
          <table cellpadding="0" cellspacing="0" border="0" >
                	<tr align="left">
                    	<td align="left" width="250px">&nbsp;<br />
                       <?php /*?> <?php
    
								  if ( $_SESSION['WEB_ADMIN_TENANTID'] == "0" ) {
									  
									  echo '<select id="hdnTenant" name="hdnTenant" width="200" style="width: 200px" size="0">';
									  if ( $transactionType == 'btnNew' ) {
										  $skills->getAllTenantsOption(0); 
									  } else {
										  $skills->getAllTenantsOption('hdnTenant'); 
									  }
									  echo '</select>';
									  echo '</td></tr>';
								  } 
							  ?> <?php */?>               
                      <input type="hidden" id="hdnTenant" name="hdnTenant" width="180" style="width: 180px"/></td>
                    </tr>
                	<tr align="left">
                    	<td align="left">Skill<br />
                        	<input type="text" id="txtSkill" name="txtSkill" width="180" style="width: 180px"/>
                        </td>
                    </tr> 
                    <tr align="left">
                    	<td align="left">Description<br />
                        	<input type="text" id="txtDesc" name="txtDesc" width="180" style="width: 180px"/>
                        </td>
                    </tr>                                   
                    <tr>
                    	<td ><!--<input type="submit" name="btnSave" id="btnSave" value="Update User"/>-->
                        	<br/>
                        	<hr />
                            <br />
                            <input type="button" name="btnSave" id="btnSave" value="Save Skill" onclick="onBtnSaveClick();"/>
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
