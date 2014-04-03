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
	
	if ( !isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		header("Location:login.php");
	}
	
	$pageCommon = new PageCommon();
	$users = new Users();
	
	if ( $_SESSION['WEB_ADMIN_TENANTID'] == "0" ) {
		$nUserCount = $users->getUsersCount();
	} else {
		$nUserCount = $users->getUsersCountByTenant($_SESSION['WEB_ADMIN_TENANTID']);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Users</title>
<link rel="stylesheet" type="text/css" href="javascript/jqueryui/css/redmond/jquery-ui-1.8.4.custom.css" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<style>
		.divchangepwd label, .divchangepwd input { display:block; }
		.divchangepwd input.text { margin-bottom:12px; width:95%; padding: .4em; }
		.divchangepwd fieldset { padding:0; border:0; margin-top:25px; }
		.divchangepwd h1 { font-size: 1.2em; margin: .6em 0; }
		.divchangepwd div#users-contain { width: 350px; margin: 20px 0; }
		.divchangepwd div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
		.divchangepwd div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
		.divchangepwd .ui-dialog .ui-state-error { padding: .3em; }
		.divchangepwd .validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>
<script src="javascript/jquery-1.9.1.js"></script>
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepalive.js"></script>
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
	function onFilterChange() {
		var nTenantID = document.getElementById("hdnTenantID").value;
		addHttpRequest("adminuser:userlist", "tenantid=" + nTenantID + "&filter=" + document.getElementById("selFilter").value);
	}
	
	function onSkillsCheckboxClick(chkBox,txtBox) {
		var ctlChkBox = document.getElementById(chkBox);
		var ctlTxtBox = document.getElementById(txtBox);
		
		if ( ctlChkBox != null && ctlTxtBox != null ) {
			if ( ctlChkBox.checked )
				ctlTxtBox.style.display = "block";
			else
				ctlTxtBox.style.display = "none";
		}
	}
	
	function displayUserInfo(userID) {
		clearSkillValues();
		clearQueues();
		clearWrapups();
		addHttpRequest("adminuser:userinfo", "userid=" + userID);
	}
	
	function onBtnSaveClick() {
		var btnSave = document.getElementById("btnSave");
		var szSkillsScore = getSkillsScore();
		var szAgentID = document.getElementById("hdnAgentID").value;
		var szUsername = document.getElementById("txtusername").value;
		var szPassword = document.getElementById("txtpassword").value;
		var szConfirmPassword = document.getElementById("txtconfirmpassword").value;
		var szLastName = document.getElementById("txtlastname").value;
		var szFirstName = document.getElementById("txtfirstname").value;
		var szUserLevel = document.getElementById("selUserLevel").value;
		var szSupervisor = document.getElementById("selSupervisor").value;
		var szTenantID = document.getElementById("hdnTenantID").value;
		var szPQueueTimeout = document.getElementById("txtpqtimeout").value;
		var nPQueueRouteType = document.getElementById("selroute").value;
		var nPSelRouteQueue = document.getElementById("selqueue").value;
		var nPSelRouteAgent = document.getElementById("selagent").value;
		var nPSelRouteExten = document.getElementById("selext").value;
		var szPSelRouteVM = document.getElementById("txtvoicemail").value;
		var szSkills = getSkillsScore();
		var szQueues = getQueuesSelected();
		var szWrapups = getPersonalWrapup();
		var szPQueueRouteValue = '';
		
		if ( szPassword != szConfirmPassword ) {
			alert("Password value doesn't match with the confirm password!!!\n\nPlease type the same password on the confirm password field!!!");
			return;
		}
		
		if ( szSupervisor == "" ) szSupervisor="0";
		if ( szPQueueTimeout == "" ) szPQueueTimeout = "0";
		
		if ( nPQueueRouteType == 1 ) {
			szPQueueRouteValue = nPSelRouteQueue;
		} else if ( nPQueueRouteType == 2 ) {
			szPQueueRouteValue = nPSelRouteAgent;
		} else if ( nPQueueRouteType == 3 ) {
			szPQueueRouteValue = szPSelRouteVM;
		} else if ( nPQueueRouteType == 4 ) {
			szPQueueRouteValue = nPSelRouteExten;
		}
			
		addHttpRequest("adminuser:saveuser", "agentid=" + szAgentID + "&uid=" + szUsername +
		   "&pwd=" + szPassword + "&lastname=" + szLastName + "&firstname=" +
		   szFirstName + "&ulevel=" + szUserLevel + "&supervisor=" + szSupervisor + 
		   "&skills=" + szSkills + "&tenantid=" + szTenantID + "&queues=" + szQueues +
		   "&pqtimeout=" + szPQueueTimeout + "&queueroutetype=" + nPQueueRouteType +
		   "&queueroutevalue=" + szPQueueRouteValue + "&wrapups=" + szWrapups);
		
	}
	
	function onBtnNewClick() {
		document.getElementById("btnSave").value = "Save User";
		document.getElementById("hdnAgentID").value = "0";
		document.getElementById("txtusername").value = "";
		document.getElementById("txtpassword").value = "";
		document.getElementById("txtconfirmpassword").value = "";
		document.getElementById("txtlastname").value = "";
		document.getElementById("txtfirstname").value = "";
		document.getElementById("selUserLevel").value = "2";
		document.getElementById("adminusertitle").innerHTML = "Add New User";
		
		clearSkillValues();
		clearQueues();
		
		document.getElementById("txtusername").focus();
	}

        function onBtnDltClick() {
                var values = "";

                for (i = 0; i < document.getElementsByName("chkuser").length; i++) {
                    if(document.getElementsByName("chkuser").item(i).checked)
                    {
                        if(values == "")
                        {
                            values += document.getElementsByName("chkuser").item(i).value;
                        }
                        else
                        {
                            values += ";" + document.getElementsByName("chkuser").item(i).value;
                        }
                    }
                }

                if(values != "")
                {
                    var agree=confirm("ARE YOU SURE ?");
                    
                    if (agree)
                    {
                        addHttpRequest("adminuser:userdelete", "users=" + values);
                    }
                }
                else
                {
                    alert("Please check user")
                }
        }
        
	function clearSkillValues() {
		var nCnt = 1;
		var chkBox = document.getElementById("chkskill" + nCnt);

		while ( chkBox != null ) {
			chkBox.checked = false;
			document.getElementById("txtskill" + nCnt).value = "0";
			document.getElementById("txtskill" + nCnt).style.display = "none";
			nCnt++;
			chkBox = document.getElementById("chkskill" + nCnt);
		}
	}
	
	function clearQueues() {
		var nCnt = 1;
		var chkBox = document.getElementById("chkqueue" + nCnt);

		while ( chkBox != null ) {
			chkBox.checked = false;
			nCnt++;
			chkBox = document.getElementById("chkqueue" + nCnt);
		}
	}
	
	function clearWrapups() {
		var nCnt = 1;
		var chkBox = document.getElementById("chkwrapup" + nCnt);

		while ( chkBox != null ) {
			chkBox.checked = false;
			nCnt++;
			chkBox = document.getElementById("chkwrapup" + nCnt);
		}
	}
	
	function getQueuesSelected() {
		var nCnt = 1;
		var chkBox = document.getElementById("chkqueue" + nCnt);
		var szReturn = "";

		while ( chkBox != null ) {
			if (chkBox.checked) {
				if ( szReturn == "" ) {
					szReturn = chkBox.value;
				} else {
					szReturn += ("|" + chkBox.value);
				}
			}
			nCnt++;
			chkBox = document.getElementById("chkqueue" + nCnt);
		}
		
		return szReturn;
	}
	
	function getPersonalWrapup() {
		var nCnt = 1;
		var chkBox = document.getElementById("chkwrapup" + nCnt);
		var szReturn = "";

		while ( chkBox != null ) {
			if (chkBox.checked) {
				if ( szReturn == "" ) {
					szReturn = chkBox.value;
				} else {
					szReturn += ("|" + chkBox.value);
				}
			}
			nCnt++;
			chkBox = document.getElementById("chkwrapup" + nCnt);
		}
		
		return szReturn;
	}
	
	function getSkillsScore() {
		var nCnt = 1;
		var chkBox = document.getElementById("chkskill" + nCnt);
		var szReturn = "";

		while ( chkBox != null ) {
			if ( chkBox.checked ) {
				var txtSkill = document.getElementById("txtskill" + nCnt);
				
				if ( szReturn == "" ) {
					szReturn = chkBox.value + ":" + txtSkill.value;
				} else {
					szReturn += ("|" + chkBox.value + ":" + txtSkill.value);
				}
			}
			nCnt++;
			chkBox = document.getElementById("chkskill" + nCnt);
		}
		
		return szReturn;
	}
	
	function validateSkillScore(txtBox) {
		var ctrlTxtBox = document.getElementById(txtBox);
		
		
		if ( ctrlTxtBox != null ) {
			szValue = ctrlTxtBox.value;

			if ( isNaN( szValue )) {
				alert("Not a numeric!!!");
				ctrlTxtBox.focus();
			} else if ( szValue > 100 ) {
				alert("Skill score should not be more than 100");
				ctrlTxtBox.focus();
			}
		}
	}
	
	function onUserLevelChange() {
		if ( document.getElementById("selUserLevel").value == "2" )
			document.getElementById("selSupervisor").style.visibility = "visible";
		else 
			document.getElementById("selSupervisor").style.visibility = "hidden";
	}
	
	function onseltenantchange() {
		var seltenant = document.getElementById("seltenant");
		var hdnTenantID = document.getElementById("hdnTenantID");
		
		if ( seltenant && hdnTenantID ) {
			if ( seltenant.value != hdnTenantID.value ) {
				hdnTenantID.value = seltenant.value;
				addHttpRequest( "adminuser:userlist", "tenantid=" + hdnTenantID.value + "&filter=0" );
			}
		}
	}
	
	function onroutechange() {
		var selqueue = document.getElementById("selqueue");
		var selagent = document.getElementById("selagent");
		var selext = document.getElementById("selext");
		var txtvoicemail = document.getElementById("txtvoicemail");
		var selroutevalue = document.getElementById("selroute").value;
		
		selqueue.style.display = "none";
		selagent.style.display = "none";
		selext.style.display = "none";
		txtvoicemail.style.display = "none";
		
		
		if ( selroutevalue ==  1 ) {
				selqueue.style.display = "block";
		} else if ( selroutevalue ==  2) {
				selagent.style.display = "block";
		} else if ( selroutevalue ==  3 ) {
				txtvoicemail.style.display = "block";
		} else if ( selroutevalue ==  4 ) {
				selext.style.display = "block";
		}

	}
	
	function onBtnQueuesClick() {
		displayContainer("divqueues");		
	}
	
	function onBtnSkillsClick() {
		displayContainer("divskills");		
	}
	
	function onBtnWrapupsClick() {
		displayContainer("divwrapups");
	}
	
	function onBtnSettingsClick() {
		displayContainer("divsettings");
	}
	
	function displayContainer(containerName) {
		var divQueues = document.getElementById("divqueues");
		var divSkills = document.getElementById("divskills");
		var divlabel = document.getElementById("divlabel");
		var divWrapups = document.getElementById("divwrapups");
		var divSettings = document.getElementById("divsettings");
		
		divQueues.style.display = "none";
		divSkills.style.display = "none";
		divWrapups.style.display  = "none";
		divSettings.style.display = "none";
		
		if ( containerName == "divqueues" ) {
			divlabel.innerHTML="Queues";
			divQueues.style.display = "block";
		} else if ( containerName == "divskills" ) {
			divlabel.innerHTML="Skills";
			divSkills.style.display = "block";
		} else if ( containerName == "divwrapups" ) {
			divlabel.innerHTML="Wrapups";
			divWrapups.style.display = "block";
		} else if ( containerName == "divsettings" ) {
			divlabel.innerHTML = "Settings"
			divSettings.style.display = "block";
		}
	}
	
</script>
</head>

<body onload="startKeepAlive();" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']); 
	$pageCommon->displayNavigation('admin', 'users');
?>

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    	<td width="15">&nbsp;</td>
    	<td valign="top" width="270">
	    	<table width="250" border="0" cellspacing="2" cellpadding="2">
            	<tr>
	    			<td colspan="2" class="tableTitle"><h2>Users</h2></td>
                </tr>
<?php
			if ( $_SESSION['WEB_ADMIN_TENANTID'] == "0" ) {
				echo '<tr>';
				echo '	<td>Tenant</td>';
				echo '  <td><select id="seltenant" style="width:180px;" onchange="onseltenantchange();">';
				echo $users->getAllTenantsOption(0);
				echo '  </select></td>';
			}
?>
				<tr>
                	<td>Filter By:</td>
                    <td>
                            <select id="selFilter" name="selFilter" width="180" style="width: 180px" onchange="onFilterChange();">
                            	<option value="0" selected="selected">All</option>
                                <option value="1">Disabled</option>
                                <option value="2">Agent</option>
                                <option value="3">Supervisor</option>
                            </select>                    
                    </td>
                </tr>
                <tr>
                	<td colspan="2">
			<div class="labelline">
                  	<?php 
						if ( $_SESSION['WEB_ADMIN_TENANTID'] == "0" ) {
                    		echo '<div id="divuserlist" name="divuserlist" style="overflow:auto;width:230px;height:330px;border:1px solid #336699;padding-left:5px">';
						} else {
							echo '<div id="divuserlist" name="divuserlist" style="overflow:auto;width:230px;height:350px;border:1px solid #336699;padding-left:5px">';
						}
					?>	
                        <?php echo $users->displayUserList($_SESSION['WEB_ADMIN_TENANTID'], 0); 
						

						?>
                        </div>
			</div>
                    </td>
                </tr>
                <tr>
                	<td colspan="2" align="center">
                    	<input type="button" name="btnNew" id="btnNew" value="New User" onclick="onBtnNewClick();" />&nbsp;
                        <input type="button" name="btnDelete" id="btnDelete" value="Delete User" onclick="onBtnDltClick()"/>
                    </td>
                </tr>
<!--                <tr>
                    	<td class="tableHeader">Tenant</td>
                        <td class="tableHeader">Username</td>
                </tr>
<?php
/*				if ( $nUserCount > 0 ) {
					// loop here
					if ( $_SESSION['WEB_ADMIN_TENANTID'] == "0" ) {
						$arrUsers = $users->getUsers(0, 50);
					} else {
						$arrUsers = $users->getUsersByTenant($_SESSION['WEB_ADMIN_TENANTID'], 0, 50);
					}
					for ( $index = 0; $index < count($arrUsers); $index++ ) {
						echo '<tr><td class="tableRow">' . $arrUsers[$index]->getTenantName() . '</td>';
						echo '<td class="tableRow">' . $arrUsers[$index]->getUsername() . '</td></tr>';
					}
				} */
?>
    			<tr>
                	<td colspan="2" class="tableFooter" align="right"><?php echo $nUserCount; ?> users</td>
                </tr> -->
            </table>
        </td>
        <td valign="top">
        	<h2><div id="adminusertitle" name="adminusertitle">Add New User</div></h2>
            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="frmUser">
            	<input type="hidden" id="hdnTenantID" name="hdnTenantID" value="<?php echo $_SESSION['WEB_ADMIN_TENANTID']; ?>" />
            	<input type="hidden" id="hdnAgentID" name="hdnAgentID" value="0" />
	        	<table cellpadding="0" cellspacing="0" border="0" >
                	<tr align="left">
                    	<td align="left" width="250px">Username<br /><input type="text" id="txtusername" name="txtusername" width="180" style="width: 180px"/></td>
                    </tr>
                	<tr align="left">
                    	<td align="left">Password<br /><input type="password" id="txtpassword" name="txtpassword" width="180" style="width: 180px" /></td>
                        <td align="left"  width="350px">&nbsp;Confirm Password<br />&nbsp;<input type="password" id="txtconfirmpassword" name="txtconfirmpassword" width="180" style="width: 180px"/></td>
                    </tr>                    
                	<tr align="left">
                    	<td align="left">Last Name<br /><input type="text" id="txtlastname" name="txtlastname" width="180" style="width: 180px"/></td>
                        <td align="left">&nbsp;First Name<br />&nbsp;<input type="text" id="txtfirstname" name="txtfirstname" width="180" style="width: 180px"/></td>
                    </tr>                    
                    <tr>
                    	<td align="left">
                        	User Level<br/>
                            <select id="selUserLevel" name="selUserLevel" width="180" style="width: 180px" onchange="onUserLevelChange();">
	                            <option value="0">Disabled</option>
                                <option value="1">Guest</option>
                                <option value="2" selected="selected">Agent</option>
                                <option value="3">Supervisor</option>
                                <?php if ( $_SESSION['WEB_ADMIN_USERLEVEL'] == "4" ) {
	                                echo '<option value="4">Administrator</option>';
								} ?>
                                <option value="5">Team Leader</option>
                            </select>
                        </td>
                        <td align="left">
                        	Supervisor<br/>
                            &nbsp;<?php echo $users->getSupervisorList($_SESSION['WEB_ADMIN_TENANTID']); ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="2">
                        	<br />
                        	<hr />
                            <table cellpadding="0" cellspacing="0" width="100%">
                            	<tr>
                                	<td align="center" valign="top" width="100px;"> 
                                    	<!-- buttons -->
                                        <input type="button" value="Skills" style="width: 80px;" onclick="onBtnSkillsClick();" /><br />
                                        <input type="button" value="Queues" style="width: 80px;" onclick="onBtnQueuesClick();"/><br />
                                        <input type="button" value="Wrapups" style="width: 80px;" onclick="onBtnWrapupsClick();"/><br />
                                     	<input type="button" value="Settings" style="width: 80px;" onclick="onBtnSettingsClick();"/>
                                    </td>
                                    <td>
                                    	<!-- windows -->
                                        <div id="divborder" class="divskills">
                                        	<div id="divlabel" style="font-weight:bold;">Skills</div>
                                        	<div id="divskills" style="overflow:auto;">
                                        		<?php echo $users->getSkillsList($_SESSION['WEB_ADMIN_TENANTID']); ?>
                                            </div>
                                            <div id="divqueues" style="overflow:auto;display:none;">
                                            	<?php echo $users->getQueuesList($_SESSION['WEB_ADMIN_TENANTID']); ?>
                                            </div>
                                            <div id="divwrapups" style="overflow:auto;display:none">
                                            	<?php echo $users->getWrapupsList($_SESSION['WEB_ADMIN_TENANTID']); ?>
                                            </div>
                                            <div id="divsettings" style="overflow:auto;display:none;">
                                            	<table cellpadding="0" cellspacing="0">
                                                	<tr>
                                                    	<td colspan="3">Personal Queues</td>
                                                    </tr>
                                   					<tr>
                                                    	<td width="10">&nbsp;</td>
                                                        <td width="60">Timeout</td>
                                                        <td><input type="text" id="txtpqtimeout" style="width: 120px;"/></td>
                                                    </tr>
                                                    <tr>
                                                    	<td width="10">&nbsp;</td>
                                                        <td width="60">Route To</td>
                                                        <td>
                                                        	<select id="selroute" style="width: 120px;" onchange="onroutechange();">
                                                            	<option value="1">Queue</option>
                                                                <option value="2">Agent</option>
                                                                <option value="3">Voicemail</option>
                                                                <option value="4">Extension</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                    	<td width="10">&nbsp;</td>
                                                        <td width="60">&nbsp;</td>
                                                        <td>
                                                        	<select id="selqueue" style="width: 120px;">
                                                            	<?php echo $users->getRouteQueuesList($_SESSION['WEB_ADMIN_TENANTID']); ?>
                                                            </select>
                                                            <select id="selagent" style="width: 120px; display: none;">
                                                            	<?php echo $users->getRouteAgentList($_SESSION['WEB_ADMIN_TENANTID']); ?>
                                                            </select>
                                                            <input type="text" id="txtvoicemail" style="width: 120px; display:none;" />
                                                            <select id="selext" style="width: 120px; display: none;">
                                                            	<?php echo $users->getRouteExtList($_SESSION['WEB_ADMIN_TENANTID']); ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <!--
                            <p><a href="#" onclick="new Effect.toggle('divmoreinfousers', 'appear'); return false;" class="morelink">+ More Settings</a></p>
                            <div id="divmoreinfousers" name="divmoreinfousers" style="display:none">
                            	<table border="0" cellpadding="0" cellspacing="0">
                                	<tr>
                                    	<td align="left">
                                        	
                                            Skills
                                            <div id="divskills" name="divskills" class="divskills">
                                                <?php echo $users->getSkillsList($_SESSION['WEB_ADMIN_TENANTID']); ?>
                                            </div>
                                        </td>
                                        <td width="20px">
                                        </td>
                                        <td align="left">
                                        	
                                            Queues
                                            <div id="divqueues" name="divqueues" class="divqueues">
                                            	<?php echo $users->getQueuesList($_SESSION['WEB_ADMIN_TENANTID']); ?>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div> -->
                        </td>
                    </tr>
                    <tr><td colspan="2">&nbsp;</td></tr>
                    <tr>
                    	<td colspan="2"><!--<input type="submit" name="btnSave" id="btnSave" value="Update User"/>-->
                        	<input type="button" name="btnSave" id="btnSave" value="Save User" onclick="onBtnSaveClick();"/>
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