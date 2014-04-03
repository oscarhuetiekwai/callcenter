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
	require_once('includes/classqueues.php');
	require_once('includes/classqueue.php');
	require_once('includes/classpagecommon.php');
	
	if ( !isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		header("Location:login.php");
	}
	
	$pageCommon = new PageCommon();
	$queues = new Queues();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Queues</title>
<link rel="stylesheet" type="text/css" href="javascript/jqueryui/css/redmond/jquery-ui-1.8.4.custom.css" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />

<script src="javascript/jquery-1.9.1.js"></script>
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepalivequeue.js"></script>
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
		var nTenantID = document.getElementById("hdnTenantID").value;
		var nQueueID = document.getElementById("hdnQueueID").value;
		var szQueue = document.getElementById("txtqueue").value;
		var szTimeout = document.getElementById("txttimeout").value;
		var szServicelevel = document.getElementById("txtservicelevel").value;
		var szWrapuptime = document.getElementById("txtwrapuptime").value;
		var szPriority =  document.getElementById("txtpriority").value;
		var szSkills = "";
		var szWrapups = "";
		var nCnt = 1;
		
		var chkSkills = document.getElementById("chkskill" + nCnt);
		while ( chkSkills ) {
			if ( chkSkills.checked ) {
				if ( szSkills == "" ) {
					szSkills = chkSkills.value;
				} else {
					szSkills += ( "," + chkSkills.value );
				}
			}
			nCnt++;
			chkSkills = document.getElementById("chkskill" + nCnt);
		}
		
		nCnt = 1;
		var chkWrapups = document.getElementById("chkwrapup" + nCnt );
		while ( chkWrapups ) {
			
			if ( chkWrapups.checked ) {
				if ( szWrapups == "" ) {
					szWrapups = chkWrapups.value;
				} else {
					szWrapups += ( "," + chkWrapups.value );
				}
			}
			nCnt++;
			chkWrapups = document.getElementById("chkwrapup" + nCnt );
		}
		
		addHttpRequest("adminqueue:savequeue", "tenantid=" + nTenantID + "&queueid=" + nQueueID + "&queue=" + szQueue +
					   "&timeout=" + szTimeout + "&servicelevel=" + szServicelevel + "&wrapuptime=" + szWrapuptime +
					   "&priority=" + szPriority + "&skills=" + szSkills + "&wrapups=" + szWrapups );
		/*var nWrapupID = document.getElementById("hdnWrapupID").value;
		var nTenantID = document.getElementById("hdnTenantID").value;
		var szWrapup = document.getElementById("txtwrapup").value;
		var szDescription = document.getElementById("txtdescription").value;
		
		addHttpRequest("adminwrapup:savewrapup", "wrapupid=" + nWrapupID + "&tenantid=" + nTenantID + "&wrapup=" + szWrapup + "&desc=" + szDescription); */
		
	}

        function onBtnDltClick() {
                var values = "";

                for (i = 0; i < document.getElementsByName("chkqueue").length; i++) {
                    if(document.getElementsByName("chkqueue").item(i).checked)
                    {
                        if(values == "")
                        {
                            values += document.getElementsByName("chkqueue").item(i).value;
                        }
                        else
                        {
                            values += ";" + document.getElementsByName("chkqueue").item(i).value;
                        }
                    }
                }

                if(values != "")
                {
                    var agree=confirm("ARE YOU SURE ?");

                    if (agree)
                    {
                        addHttpRequest("adminqueue:queuedelete", "queues=" + values);
                    }
                }
                else
                {
                    alert("Please check Queue")
                }
        }
	
	function reloadWrapupList() {
		/*var nTenantID = document.getElementById("hdnTenantID").value;
		
		addHttpRequest("adminwrapup:wrapuplist", "tenantid=" + nTenantID); */
	}
	
	function onQueueClick(nQueueID) {
		addHttpRequest("adminqueue:queueinfo", "queueid=" + nQueueID); 
	}
	
	
	function onBtnNewClick() {
		document.getElementById("adminqueuetitle").innerHTML = "Add New Queue";
		document.getElementById("btnSave").value = "Save Queue";
		document.getElementById("hdnQueueID").value = "0";
		document.getElementById("txtqueue").value = "";
		document.getElementById("txttimeout").value = "0";
		document.getElementById("txtservicelevel").value = "20";
		document.getElementById("txtwrapuptime").value = "0";
		document.getElementById("txtpriority").value = "0";
		
		// clear the checkbox first
		nCntX = 1;
		var chkSkill = document.getElementById("chkskill" + nCntX);
		while ( chkSkill ) {
			chkSkill.checked = false;
			nCntX++;
			chkSkill = document.getElementById("chkskill" + nCntX);
		}	
		
		nCntX = 1;
		var chkWrapup = document.getElementById("chkwrapup" + nCntX);
		while ( chkWrapup ) {
			chkWrapup.checked = false;
			nCntX++;
			chkWrapup = document.getElementById("chkwrapup" + nCntX);
		}
		
		document.getElementById("txtqueue").focus();
/*		document.getElementById("hdnWrapupID").value = "0";
		document.getElementById("txtwrapup").value = "";
		document.getElementById("txtdescription").value = "";
		document.getElementById("adminwrapuptitle").innerHTML = "Add New Wrapup";
		document.getElementById("btnSave").value ="Save Wrapup";
		document.getElementById("txtwrapup").focus(); */
	}
	
	function onBtnWrapupsClick() {
		displayContainer("divWrapups");		
	}
	
	function onBtnSkillsClick() {
		displayContainer("divSkills");		
	}
	
	function displayContainer(containerName) {
		var divWrapups = document.getElementById("divWrapups");
		var divSkills = document.getElementById("divSkills");
		
		divWrapups.style.display = "none";
		divSkills.style.display = "none";
		
		if ( containerName == "divWrapups" ) divWrapups.style.display = "block";
		if ( containerName == "divSkills" ) divSkills.style.display = "block";
	}
	
</script>
</head>

<body onload="startKeepAlive();" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']); 
	$pageCommon->displayNavigation('admin', 'queues');
?>

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    	<td width="15">&nbsp;</td>
    	<td valign="top" width="270">
	    	<table width="250" border="0" cellspacing="2" cellpadding="2">
            	<tr>
	    			<td colspan="2" class="tableTitle"><h2>Queues</h2></td>
                </tr>
<?php
			if ( $_SESSION['WEB_ADMIN_TENANTID'] == "0" ) {
				echo '<tr>';
				echo '	<td>Tenant</td>';
				echo '  <td>';
				echo '  </td>';
			}
?>
                <tr>
                	<td colspan="2">
                    	<div class="labelline">
                    	<div id="divqueuelist" style="overflow:auto;width:230px;height:370px;border:1px solid #336699;padding-left:5px">
                        	<?php echo $queues->displayQueueList($_SESSION['WEB_ADMIN_TENANTID']); ?>
                        </div>
                        </div>
                    </td>
                </tr>
                <tr>
                	<td colspan="2" align="center">
                    	<input type="button" name="btnNew" id="btnNew" value="New Queue" onclick="onBtnNewClick();" />&nbsp;
                        <input type="button" name="btnDelete" id="btnDelete" value="Delete Queue" onclick="onBtnDltClick();" />
                    </td>
                </tr>
            </table>
        </td>
        <td valign="top">
        	<h2><div id="adminqueuetitle">Add New Queue</div></h2>
            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="frmWrapup">
            	<input type="hidden" id="hdnTenantID" value="<?php echo $_SESSION['WEB_ADMIN_TENANTID']; ?>" />
            	<input type="hidden" id="hdnQueueID" value="0" />
	        	<table cellpadding="0" cellspacing="0" border="0" >
                	<tr align="left">
                    	<td align="left" style="width:250px;">Queue<br /><input type="text" id="txtqueue" style="width: 180px"/></td>
                        <td align="left">Ringing Timeout On Agent<br /><input type="text" id="txttimeout" style="width: 180px"/>&nbsp;sec(s)</td>
                    </tr>
                	<tr align="left">
                    	<td align="left">Priority<br /><input type="text" id="txtpriority" style="width: 180px"/></td>
                        <td align="left" style="width:250px;">Service Level<br /><input type="text" id="txtservicelevel" style="width: 180px"/>&nbsp;sec(s)</td>        
                    </tr>                    
                	<tr align="left">
                    	<td align="left">Wrapup Time<br /><input type="text" id="txtwrapuptime" style="width: 180px"/>&nbsp;sec(s)</td>
                        <!-- <td align="left"><br /><input type="checkbox" id="chkforcewrapup" value="Force Wrapup" /> Force wrapup</td> -->
                    </tr>                                                            
                    <tr>
                    	<td colspan="2">
                        	<br/>
                        	<hr />
                        	<table cellpadding="0" cellspacing="0" border="0" >
                            	<tr valign="top">
                                	<td>
                                    	<input type="button" id="btnskills" name="btnskills" style="width: 120px" value="Skills" onclick="onBtnSkillsClick();" />                                   		<br />
                                        <input type="button" id="btnwrapup" name="btnwrapup" style="width: 120px" value="Wrapups" onclick="onBtnWrapupsClick();" />
                                    </td>
                                    <td>
                                    	<div id="divSkills" style="overflow:auto; width:400px; height:230px; border:1px solid #336699; padding-left:5px;">
                                        	<table cellpadding="2" cellspacing="0" border="0" width="100%">
                                            	<tr><td colspan="2"><b>Skills</b></td></tr>
                                            	<tr align="left" valign="top">
                                                	<td width="65%">
                                                        <div id="divskills" class="divqueues">
                                                        	<?php echo $queues->getSkillsList($_SESSION['WEB_ADMIN_TENANTID']); ?>
                                            			</div>
                                                    </td>
                                                    <td width="35%">
                                                    	Please select the required skills for this campaign. You can select as many as you want.
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    	<div id="divWrapups" style="overflow:auto; width:400px; height:230px; border:1px solid #336699; padding-left:5px;display: none;">
                                        	<table cellpadding="2" cellspacing="0" border="0" width="100%">
                                            	<tr><td colspan="2"><b>Wrapups</b></td></tr>
                                            	<tr align="left" valign="top">
                                                	<td width="65%">
                                                        <div id="divwrapups" class="divqueues">
                                                        	<?php echo $queues->getWrapupsList($_SESSION['WEB_ADMIN_TENANTID']); ?>
                                            			</div>
                                                    </td>
                                                    <td width="35%">
                                                    	Please select the required wrapups for this campaign. You can select as many as you want.
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </table>                                                        
                            <br />
                        	<input type="button" name="btnSave" id="btnSave" value="Save Queue" onclick="onBtnSaveClick();"/>
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