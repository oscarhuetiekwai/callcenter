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
	require_once('includes/classoutbounds.php');
	require_once('includes/classoutbound.php');
	require_once('includes/classpagecommon.php');
	
	if ( !isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		header("Location:login.php");
	}
	
	$pageCommon = new PageCommon();
	$outbounds = new Outbounds();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Outbounds</title>
<link rel="stylesheet" type="text/css" href="javascript/jqueryui/css/redmond/jquery-ui-1.8.4.custom.css" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepaliveoutbound.js"></script>
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
	function onImportCSV() {
		document.getElementById("divimportlist").style.display = "block";
	}

	function onSelOutboundChange() {
		var lbloutboundsched = document.getElementById("lbloutboundsched");
		
		if ( document.getElementById("seloutboundtype").value == "0" ) {
			if ( divoutboundscheds.style.display != "none" )
				var effectOutboundSched = new Effect.toggle('divoutboundscheds' , 'appear');
		} else {
			if ( divoutboundscheds.style.display == "none" )
				var effectOutboundSched = new Effect.toggle('divoutboundscheds' , 'appear');
		}
	}
	
	function onFilterChange() {
		var nTenantID = document.getElementById("hdnTenantID").value;
		var szFilter = document.getElementById("selFilter").value;
		
		addHttpRequest("adminoutbound:outboundlist", "tenantid=" + nTenantID + "&filter=" + szFilter);
	}
	
	function onChkDayClick(nIndex) {
		if (document.getElementById("chkday" + nIndex).checked ) {
			document.getElementById("txtstartday" + nIndex).style.visibility = "visible";
			document.getElementById("txtendday" + nIndex).style.visibility = "visible";			
			document.getElementById("tdto" + nIndex).style.visibility = "visible";			
		} else {
			document.getElementById("txtstartday" + nIndex).style.visibility = "hidden";
			document.getElementById("txtendday" + nIndex).style.visibility = "hidden";
			document.getElementById("tdto" + nIndex).style.visibility = "hidden";			
		}
	}
	
	function onBtnWrapupsClick() {
		displayContainer("divWrapups");		
	}
	
	function onBtnSkillsClick() {
		displayContainer("divSkills");		
	}
	
	function onBtnCustomerListClick() {
		displayContainer("divCustomers");		
	}
	
	function onBtnNewClick() {
		document.getElementById("hdnOutboundID").value = "0"
		document.getElementById("seloutboundtype").value = "0";
		document.getElementById("txtoutbound").value = "";
		document.getElementById("txtstartdate").value = "";
		document.getElementById("txtenddate").value = "";
		
		onSelOutboundChange();
		
		for ( nCnt = 0; nCnt < 7; nCnt++ ) {
			document.getElementById("chkday" + nCnt ).checked = false;
			document.getElementById("txtstartday" + nCnt).value = "00:00:00";
			document.getElementById("txtendday" + nCnt ).value = "00:00:00";
			onChkDayClick( nCnt );
		}
		
		nCnt = 1;
		var chkSkills = document.getElementById("chkskill" + nCnt ) ;
		
		while ( chkSkills ) {
			chkSkills.checked = false;
			
			nCnt++;
			chkSkills = document.getElementById("chkskill"  + nCnt);
		}
		
	
		// getting wrapup codes
		nCnt = 1;
		var chkWrapups = document.getElementById("chkwrapup" + nCnt );
		
		while ( chkWrapups ) {
			chkWrapups.checked = false;
			
			nCnt++;
			chkWrapups = document.getElementById("chkwrapup" + nCnt )	;	
		}
		
		document.getElementById("txtoutbound").focus();
		
	}
	
	function displayContainer(containerName) {
		var divWrapups = document.getElementById("divWrapups");
		var divSkills = document.getElementById("divSkills");
		var divCustomers = document.getElementById("divCustomers");
		
		if ( ( containerName != "divWrapups" ) &&  (divWrapups != null ) ) divWrapups.style.display = "none";
		if ( ( containerName != "divSkills" )  && ( divSkills != null ) ) divSkills.style.display = "none";
		if ( ( containerName != "divCustomers" ) && ( divCustomers != null ) ) divCustomers.style.display = "none";
		
		if ( ( containerName == "divWrapups" ) && ( divWrapups != null ) ) {
			if ( divWrapups.style.display == "none" ) {
				var effectWrapups = new Effect.toggle('divWrapups', 'appear' );
			}
		} else if ( ( containerName == "divSkills" ) && ( divSkills != null ) ) {
			if ( divSkills.style.display == "none") {
				var effectSkills = new Effect.toggle('divSkills', 'appear');
			}
		} else if ( ( containerName == "divCustomers" ) && ( divCustomers != null ) ) {
			if ( divCustomers.style.display == "none" ) {
				var effectCustomers = new Effect.toggle('divCustomers', 'appear');
			}
		}
	}
	
	function onBtnSaveClick() {
		var nTenantID = document.getElementById("hdnTenantID").value;
		var nOutboundID = document.getElementById("hdnOutboundID").value;
		var nOutboundType = document.getElementById("seloutboundtype").value;
		var szOutbound = document.getElementById("txtoutbound").value;
		var szOutboundStartDate = document.getElementById("txtstartdate").value;
		var szOutboundEndDate = document.getElementById("txtenddate").value;
		var szOutboundScheds = "";
		var szOutboundSkills = "&skills=";
		var szOutboundWrapups = "&wrapups=";
		
		if ( szOutbound == "" ) {
			alert("Please input outbound name!");
			document.getElementById("txtoutbound").focus();
			
			return;
		}
		
		// getting the outbound schedules
		for ( nCnt = 0; nCnt < 7; nCnt++ ) {
			if ( document.getElementById("chkday" + nCnt ).checked ) {
				var szTimeStart = document.getElementById("txtstartday" + nCnt).value;
				var szTimeEnd = document.getElementById("txtendday" + nCnt ).value;
				
				if ( szTimeStart == "" ) {
					alert("Please enter time start!!!");
					document.getElementById("txtstartday" + nCnt).focus();
					
					return;
				} else if ( szTimeEnd == "" ) {
					alert("Please enter time end!!!");
					document.getElementById("txtendday" + nCnt).focus();
					
					return;
				}
				
				
				szOutboundScheds += ( "&chkday" + nCnt + "=1" + "&timestart" + nCnt + "=" + szTimeStart + 
									 "&timeend" + nCnt + "=" + szTimeEnd );
				
			}
		}
		
		// getting outbound skills
		nCnt = 1;
		var chkSkills = document.getElementById("chkskill" + nCnt ) ;
		
		while ( chkSkills ) {
			if ( chkSkills.checked ) {
				szOutboundSkills += (chkSkills.value + ",");
			}
			
			nCnt++;
			chkSkills = document.getElementById("chkskill"  + nCnt);
		}
		
	
		// getting wrapup codes
		nCnt = 1;
		var chkWrapups = document.getElementById("chkwrapup" + nCnt );
		
		while ( chkWrapups ) {
			if ( chkWrapups.checked ) {
				szOutboundWrapups += ( chkWrapups.value + "," );
			}
			
			nCnt++;
			chkWrapups = document.getElementById("chkwrapup" + nCnt )	;	
		}
		
		if ( szOutboundSkills.length > 8 )
			szOutboundSkills = szOutboundSkills.substr( 0, szOutboundSkills.length - 1 );
			
		if ( szOutboundWrapups.length > 9 )
			szOutboundWrapups = szOutboundWrapups.substr( 0, szOutboundWrapups.length - 1 );
			
		addHttpRequest("adminoutbound:saveoutbound", "outboundid=" + nOutboundID + "&outboundtype=" + nOutboundType +
					   "&outbound=" + szOutbound + "&outboundstart=" + szOutboundStartDate + "&tenantid=" + nTenantID +
					   "&outboundend=" + szOutboundEndDate + szOutboundScheds + szOutboundSkills + szOutboundWrapups );
	}
	
	function onOutboundClick(nOutboundID) {
		addHttpRequest("adminoutbound:outboundinfo", "outboundid=" + nOutboundID);
	}
	
</script>
</head>

<body onload="startKeepAlive();">
<?php
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']); 
	$pageCommon->displayNavigation('admin', 'outbounds');
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    	<td width="15">&nbsp;</td>
    	<td valign="top" width="270">
	    	<table width="250" border="0" cellspacing="2" cellpadding="2">
            	<tr>
	    			<td colspan="2" class="tableTitle"><h2>Outbounds</h2></td>
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
                	<td>Filter By:</td>
                    <td>
                            <select id="selFilter" name="selFilter" style="width: 180px" onchange="onFilterChange();">
                            	<option value="0" selected="selected">All</option>
                                <option value="1">Manual Dialer</option>
                                <option value="2">Preview Dialer</option>
                            </select>                    
                    </td>
                </tr>
                <tr>
                	<td colspan="2">
                    	<div id="divoutboundlist" style="overflow:auto;width:230px;height:400px;border:1px solid #336699;padding-left:5px">
                        	<?php echo $outbounds->displayOutboundList($_SESSION['WEB_ADMIN_TENANTID'], 0); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                	<td colspan="2" align="center">
                    	<input type="button" name="btnNew" id="btnNew" value="New Outbound" onclick="onBtnNewClick();" />&nbsp;
                        <input type="button" name="btnDelete" id="btnDelete" value="Delete Outbound" />
                    </td>
                </tr>
            </table>
        </td>
        <td valign="top">
        	<h2><div id="adminoutboundtitle">Add New Campaign</div></h2>
            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="frmOutbound">
            	<input type="hidden" id="hdnTenantID" name="hdnTenantID" value="<?php echo $_SESSION['WEB_ADMIN_TENANTID']; ?>" />
            	<input type="hidden" id="hdnOutboundID" name="hdnOutboundID" value="0" />
	        	<table cellpadding="0" cellspacing="0" border="0" >
                	<tr>
                    	<td>
                        	<table cellpadding="0" cellspacing="0" border="0" width="200px" >
                                <tr align="left">
                                    <td align="left" width="250px">Outbound<br /><input type="text" id="txtoutbound" name="txtoutbound" style="width: 180px"/></td>
                                </tr>
                                <tr align="left">
                                    <td align="left">Outbound Type<br />
                                        <select id="seloutboundtype" name="seloutboundtype" style="width: 180px" onchange="onSelOutboundChange();">
                                            <option value="0">Manual Dialer</option>
                                            <option value="1" selected="selected">Preview Dialer</option>
                                        </select>
                                    </td>
                                </tr>                    
                                <tr align="left">
                                    <td align="left">Start Date (yyyy-MM-dd)<br /><input type="text" id="txtstartdate" name="txtstartdate" style="width: 180px"/></td>
                                </tr>                    
                                <tr align="left">
                                    <td align="left">End Date (yyyy-MM-dd)<br /><input type="text" id="txtenddate" name="txtenddate" style="width: 180px"/></td>
                                </tr>     
                             </table>   
                         </td>
                         <td>
                         	<table cellpadding="0" cellspacing="0" border="0" >
                            	<tr valign="top" align="left" height="187">
                                	<td>
                                    	<div id="divoutboundscheds" style="overflow:auto; width:260px; height:185px; border:1px solid #336699; padding-left:5px">										<b>Outbound Schedules</b>
                                        	<table cellpadding="0" cellspacing="0" border="0" >
                                            	<tr>
                                                	<td><input type="checkbox" id="chkday0" name="chkday0" onclick="onChkDayClick(0);" /></td>
                                                    <td width="90">Sunday</td>
                                                    <td><input type="text" id="txtstartday0" name="txtstartday0" onblur="onValidateTime('txtstartday0');" style="width: 60px; visibility:hidden;" value="00:00:00"/></td>
                                                    <td id="tdto0" style="visibility:hidden;">&nbsp;to&nbsp;</td>
                                                    <td><input type="text" id="txtendday0" name="txtendday0" onblur="onValidateTime('txtendday0');" style="width: 60px; visibility:hidden;" value="00:00:00"/></td>
                                                </tr>
                                                <tr>
                                                	<td><input type="checkbox" id="chkday1" name="chkday1" onclick="onChkDayClick(1);" /></td>
                                                    <td>Monday</td>
                                                    <td><input type="text" id="txtstartday1" name="txtstartday1" onblur="onValidateTime('txtstartday1');" style="width: 60px; visibility:hidden;" value="00:00:00"/></td>
                                                    <td id="tdto1" style="visibility:hidden;">&nbsp;to&nbsp;</td>
                                                    <td><input type="text" id="txtendday1" name="txtendday1" onblur="onValidateTime('txtendday1');" style="width: 60px; visibility:hidden;" value="00:00:00"/></td>
                                                </tr>
                                                <tr>
                                                	<td><input type="checkbox" id="chkday2" name="chkday2" onclick="onChkDayClick(2);" /></td>
                                                    <td>Tuesday</td>
                                                    <td><input type="text" id="txtstartday2" name="txtstartday2" onblur="onValidateTime('txtstartday2');" style="width: 60px; visibility:hidden;" value="00:00:00"/></td>
                                                    <td id="tdto2" style="visibility:hidden;">&nbsp;to&nbsp;</td>
                                                    <td><input type="text" id="txtendday2" name="txtendday2" onblur="onValidateTime('txtendday2');" style="width: 60px; visibility:hidden;" value="00:00:00"/></td>
                                                </tr>
                                                <tr>
                                                	<td><input type="checkbox" id="chkday3" name="chkday3" onclick="onChkDayClick(3);" /></td>
                                                    <td>Wednesday</td>
                                                    <td><input type="text" id="txtstartday3" name="txtstartday3" onblur="onValidateTime('txtstartday3');" style="width: 60px; visibility:hidden;" value="00:00:00"/></td>
                                                    <td id="tdto3" style="visibility:hidden;">&nbsp;to&nbsp;</td>
                                                    <td><input type="text" id="txtendday3" name="txtendday3" onblur="onValidateTime('txtendday3');" style="width: 60px; visibility:hidden;" value="00:00:00"/></td>
                                                </tr>
                                                <tr>
                                                	<td><input type="checkbox" id="chkday4" name="chkday4" onclick="onChkDayClick(4);" /></td>
                                                    <td>Thursday</td>
                                                    <td><input type="text" id="txtstartday4" name="txtstartday4" onblur="onValidateTime('txtstartday4');" style="width: 60px; visibility:hidden;" value="00:00:00"/></td>
                                                    <td id="tdto4" style="visibility:hidden;">&nbsp;to&nbsp;</td>
                                                    <td><input type="text" id="txtendday4" name="txtendday4" onblur="onValidateTime('txtendday4');" style="width: 60px; visibility:hidden;" value="00:00:00"/></td>
                                                </tr>
                                                <tr>
                                                	<td><input type="checkbox" id="chkday5" name="chkday5" onclick="onChkDayClick(5);" /></td>
                                                    <td>Friday</td>
                                                    <td><input type="text" id="txtstartday5" name="txtstartday5" onblur="onValidateTime('txtstartday5');" style="width: 60px; visibility:hidden;" value="00:00:00"/></td>
                                                    <td id="tdto5" style="visibility:hidden;">&nbsp;to&nbsp;</td>
                                                    <td><input type="text" id="txtendday5" name="txtendday5" onblur="onValidateTime('txtendday5');" style="width: 60px; visibility:hidden;" value="00:00:00"/></td>
                                                </tr>
												<tr>
                                                	<td><input type="checkbox" id="chkday6" name="chkday6" onclick="onChkDayClick(6);" /></td>
                                                    <td>Saturday</td>
                                                    <td><input type="text" id="txtstartday6" name="txtstartday6" onblur="onValidateTime('txtstartday6');" style="width: 60px; visibility:hidden;" value="00:00:00"/></td>
                                                    <td id="tdto6" style="visibility:hidden;">&nbsp;to&nbsp;</td>
                                                    <td><input type="text" id="txtendday6" name="txtendday6" onblur="onValidateTime('txtendday6');" style="width: 60px; visibility:hidden;" value="00:00:00"/></td>
                                                </tr>                                                
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                         </td>               
                    </tr>
                    <tr><td colspan="2"><hr/></td></tr>
                    <tr>
                    	<td colspan="2">
                        	<table cellpadding="0" cellspacing="0" border="0" style="width:550px" >
                            	<tr valign="top">
                                	<td>
                                    	<input type="button" id="btnskills" name="btnskills" style="width: 120px" value="Skills" onclick="onBtnSkillsClick();" />                                   		<br />
                                        <input type="button" id="btnwrapup" name="btnwrapup" style="width: 120px" value="Wrapups" onclick="onBtnWrapupsClick();" />
                                        <br />
                                        <!--<input type="button" id="btncustomerlist" name="btncustomerlist" style="width: 120px" value="Customer List" onclick="onBtnCustomerListClick();" />-->
                                    </td>
                                    <td style="height:235px;">
                                    	<div id="divSkills" style="overflow:auto; width:400px; height:230px; border:1px solid #336699; padding-left:5px;">
                                        	<table cellpadding="2" cellspacing="0" border="0" width="100%">
                                            	<tr><td colspan="2"><b>Skills</b></td></tr>
                                            	<tr align="left" valign="top">
                                                	<td width="65%">
                                                        <div id="divskills" class="divqueues">
                                                        	<?php echo $outbounds->getSkillsList($_SESSION['WEB_ADMIN_TENANTID']); ?>
                                            			</div>
                                                    </td>
                                                    <td width="35%">
                                                    	Please select the required skills for this campaign. You can select as many as you want.
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    	<!--<div id="divWrapups" class="divOutboundWrapups"> -->
                                        <div id="divWrapups" style="overflow:auto; width:400px; height:230px; border:1px solid #336699; padding-left:5px; display:none">
                                        	<table cellpadding="2" cellspacing="0" border="0" width="100%">
                                            	<tr><td colspan="2"><b>Wrapups</b></td></tr>
                                            	<tr align="left" valign="top">
                                                	<td width="65%">
                                                        <div id="divwrapups" class="divqueues">
                                                        	<?php echo $outbounds->getWrapupsList($_SESSION['WEB_ADMIN_TENANTID']); ?>
                                            			</div>
                                                    </td>
                                                    <td width="35%">
                                                    	Please select the required wrapups for this campaign. You can select as many as you want.
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <!--
                                        <div id="divCustomers" style="overflow:auto; width:400px; height:230px; border:1px solid #336699; padding-left:5px; display:none">
                                        	<b>Customers</b>
                                            <hr />
                                        	<table cellpadding="2" cellspacing="0" border="0" width="100%">
                                            	<tr>
                                                	<td width="280">
                                                    	<table cellpadding="2" cellspacing="0" border="0" width="100%">
                                                        	<tr>
                                                            	<td>Total List Count:</td>
                                                                <td>0</td>
                                                            </tr>
                                                            <tr>
                                                            	<td>Total Processed Count:</td>
                                                                <td>0</td>
                                                            </tr>
                                                            <tr>
                                                            	<td>Total Unprocessed Count:</td>
                                                                <td>0</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td>
                                                    	<input type="button" id="btnShowCustomerList" value="Show Customer List" style="width: 140px;" /><br/>
                                                    	<input type="button" id="btnImportCSV" onclick="onImportCSV();" value="Import CSV" style="width: 140px;" /><br />
                                                        <input type="button" id="btnImportODBC" value="Import ODBC" style="width: 140px;" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                	<td colspan="2">
                                                    	<hr />
                                                    	<b>Show Customer List</b> - will display all customers that are listed in to this campaign<br/>
                                                        <b>Import CSV</b> - this will import a customer csv file into our database.<br/>
                                                        <b>Import ODBC</b> - this will import a customer database into our database.<br/>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        -->
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="2"><!--<input type="submit" name="btnSave" id="btnSave" value="Update User"/>-->
                            <hr />
                        	<input type="button" name="btnSave" id="btnSave" value="Save Campaign" onclick="onBtnSaveClick();"/>
                        </td>
                    </tr>
    	        </table>
            </form>
        </td>
    </tr>
</table>

<!-- accessories div windows -->
<div id="divimportlist" style="visibility:hidden; width:150px; height:150px;float: none;">
	<p> Testing Float </p>
</div>
<?php $pageCommon->displayDivChangePwd(); ?>
</body>
</html>