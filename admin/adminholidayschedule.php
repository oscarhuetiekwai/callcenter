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
	require_once('includes/classpagecommon.php');
	
	if ( !isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		header("Location:login.php");
	}
	
	$pageCommon = new PageCommon();
	//$nSkillCount = $skills->getSkillsCount($_SESSION['WEB_ADMIN_TENANTID']);
	$transactionType = 'btnNew';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Holidays Schedule</title>
<link rel="stylesheet" type="text/css" href="javascript/jqueryui/css/redmond/jquery-ui-1.8.4.custom.css" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.datepick.css"/>
<link rel="stylesheet" type="text/css" href="css/style.css" />

<script src="javascript/jquery-1.9.1.js"></script>
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepaliveholidayschedule.js"></script>
<script language="javascript" type="text/javascript" src="javascript/jqueryui/js/jquery-1.4.2.min.js"></script>
<script language="javascript" type="text/javascript" src="javascript/jqueryui/js/jquery-ui-1.8.4.custom.min.js"></script>
<script language="javascript" type="text/javascript" src="javascript/timepicker.js"></script>
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
                var valid = validateFields();
                if(valid == false)
                {
                    return false;
                }
		var nTenantID = document.getElementById("hdnTenantID").value;
		var szTenant = document.getElementById("hdnTenant").value;
		var szHolidayFrom = document.getElementById("txtHolidayFrom").value;
                var szHolidayTo = document.getElementById("txtHolidayTo").value;
		var szDesc = document.getElementById("txtDesc").value;
                
		addHttpRequest("adminholidayschedule:saveholiday", "tenantid=" + nTenantID + "&tenant=" + szTenant + "&holidayfrom=" + szHolidayFrom + "&holidayto=" + szHolidayTo + "&desc=" + szDesc);
		
	}

        function onBtnUpdateClick() {
                var valid = validateFields();
                if(valid == false)
                {
                    return false;
                }
		var nTenantID = document.getElementById("hdnTenantID").value;
		var szTenant = document.getElementById("hdnTenant").value;
		var szHolidayFrom = document.getElementById("txtHolidayFrom").value;
                var szHolidayTo = document.getElementById("txtHolidayTo").value;
		var szDesc = document.getElementById("txtDesc").value;
                
		addHttpRequest("adminholidayschedule:updateholiday", "tenantid=" + nTenantID + "&tenant=" + szTenant + "&holidayfrom=" + szHolidayFrom + "&holidayto=" + szHolidayTo + "&desc=" + szDesc);

	}

        function validateFields()
        {
                if(document.getElementById("txtHolidayFrom").value == "")
                {
                    alert("From Date Time be cannot blank.");
                    return false;
                }
                else
                    var szHolidayFrom = document.getElementById("txtHolidayFrom").value;

                if(document.getElementById("txtHolidayTo").value == "")
                {
                    alert("To Date Time be cannot blank.");
                    return false;
                }
                else
                    var szHolidayTo = document.getElementById("txtHolidayTo").value;

                if(document.getElementById("txtDesc").value == "")
                {
                    alert("Description be cannot blank.");
                    return false;
                }
                //Date Validations
                
                var arrHolidayFrom = szHolidayFrom.split(" ");//yyyy-mm-dd hh:mm:ss
                var arrHolidayTo = szHolidayTo.split(" ");//yyyy-mm-dd hh:mm:ss
                var arrDateFrom = arrHolidayFrom[0].split("-");//yyyy-mm-dd
                var arrTimeFrom = arrHolidayFrom[1].split(":");//hh:mm:ss
                var arrDateTo = arrHolidayTo[0].split("-");//yyyy-mm-dd
                var arrTimeTo = arrHolidayTo[1].split(":");//hh:mm:ss
                
                //DateTime Validations
                var holidayTimeFrom = new Date( arrDateFrom[0],arrDateFrom[1], arrDateFrom[2], arrTimeFrom[0], arrTimeFrom[1], arrTimeFrom[2], 0);

                var holiodayTimeTo = new Date( arrDateTo[0], arrDateTo[1], arrDateTo[2], arrTimeTo[0], arrTimeTo[1], arrTimeTo[2], 0);

                if (holidayTimeFrom > holiodayTimeTo)
                {
                    alert("Invalid range, From Date Time is more than To Date Time");
                    return false;
                }
                else if (szHolidayFrom == szHolidayTo)
                {
                    alert("Invalid range, From Date Time is same as To Date Time");
                    return false;
                }

                return true;
        }
	
	function reloadHolidayList() {
		var nTenantID = document.getElementById("hdnTenantID").value;
		
		addHttpRequest("adminholidayschedule:holidayslist", "tenantid=" + nTenantID);
	}
	
	function onHolidayInfo(nHolidayFrom,nHolidayTo) {
		addHttpRequest("adminholidayschedule:holidayinfo", "holidayfrom=" + nHolidayFrom + "&holidayto=" + nHolidayTo);
	}
	
	
	function onBtnNewClick() {
		document.getElementById("hdnTenant").value = "";
		document.getElementById("txtHolidayFrom").value = "";
                document.getElementById("txtHolidayTo").value = "";
		document.getElementById("txtDesc").value = "";
		
		document.getElementById("adminskilltitle").innerHTML = "Add New Holiday";
		document.getElementById("btnSave").value ="Save Holiday";
                document.getElementById("txtHolidayFrom").disabled = false;
                document.getElementById("txtHolidayTo").disabled = false;
                //document.getElementsByName("calendericon").item(0).height = "15";
                //document.getElementsByName("calendericon").item(0).width = "19";
		document.getElementsByName("txtHolidayFrom").item(0).focus();
                document.getElementById("btnSave").setAttribute("onclick","onBtnSaveClick();" );
	}

        function onBtnDeleteClick() {
                var values = "";
                
                for (i = 0; i < document.getElementsByName("chkholiday").length; i++) {
                    if(document.getElementsByName("chkholiday").item(i).checked)
                    {
                        if(values == "")
                        {
                            values += document.getElementsByName("chkholiday").item(i).value;
                        }
                        else
                        {
                            values += ";" + document.getElementsByName("chkholiday").item(i).value;
                        }
                    }
                }

                if(values != "")
                {
                    var agree=confirm("ARE YOU SURE ?");
                    if (agree)
                        addHttpRequest("adminholidayschedule:deleteholiday", "values=" + values);
                }
                else
                {
                    alert("Please check holiday")
                }
	}

        function toggleHolidayDetails(holidayToggleID){
            var div1 = document.getElementById(holidayToggleID);
            if (div1.style.display == 'none') {
                div1.style.display = 'block';
            } else {
                div1.style.display = 'none';
            }
        }

        function generateEnabled(id)
	{
		//disable the button
		$("#" + id).attr("disabled", false);
	}

        $(document).ready(function()
	{
		//disable the button
		$("#btn_report_date").attr("disabled", true);

		$('.reportLink').click(function()
		{
			$('.reportLink').closeDOMWindow({anchoredClassName:'exampleWindow6',eventType:'click'});
		})

		$("#report_date").focus(function(){
			($(this).val() != "") ? $("#btn_report_date").attr("disabled", false) : $("#btn_report_date").attr("disabled", true);
		});

		//date picker
		$("#report_date").datepick({
			                       onSelect: function() {($("#report_date").val() != "") ? $("#btn_report_date").attr("disabled", false) : $("#btn_report_date").attr("disabled", true);},
			                       showTrigger: '<img name="calendericon" onchange="return setFocus(\'report_date\');" onclick="return generateEnabled(\'btn_report_date\');" border="0" src="images/be_calendar.gif" class="trigger" id="calImg" style="padding-left:10px;">'});


	}
	);

        function onLoad() {
            startKeepAlive();
            $('#txtHolidayFrom').datetimepicker( {
                    showSecond: true,
                    dateFormat: 'yy-mm-dd',
                    timeFormat: 'hh:mm:ss'
                    } );
            $('#txtHolidayTo').datetimepicker( {
                    showSecond: true,
                    dateFormat: 'yy-mm-dd',
                    timeFormat: 'hh:mm:ss'
                    } );
            //setTenantID(nAdminTenantID);
        }
	
</script>
</head>

<body onload="onLoad();reloadHolidayList();startKeepAlive();" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']); 
	$pageCommon->displayNavigation('admin', 'holiday');
?>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    	<td width="15">&nbsp;</td>
    	<td valign="top" width="270">
	    	<table width="250" border="0" cellspacing="2" cellpadding="2">
            	<tr>
	    			<td colspan="2" class="tableTitle"><h2>Holidays</h2></td>
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
                    	<div class="labelline" id="divholidaylist" name="divholidaylist" style="overflow:auto;width:230px;height:300px;border:1px solid #336699;padding-left:5px">
                        <!--<b>Total number of skills: <?php /*?><?php echo $nSkillCount; ?><?php */?></b><br/><br/>
                    	  <b>List of Skills</b>-->
                        </div>                                             
                    </td>
                </tr>
                <tr>
                	<td colspan="2" align="center">
                    	<input type="button" name="btnNew" id="btnNew" value="New Holiday" onclick="onBtnNewClick();" />&nbsp;
                        <input type="button" name="btnDelete" id="btnDelete" value="Delete Holiday" onclick="onBtnDeleteClick();" />
                    </td>
                </tr>
            </table>
        </td>
        <td valign="top">
       	  <h2 ><div id="adminskilltitle" name="adminskilltitle">Add New Holiday</div></h2>
          <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="frmSkill">
          <input type="hidden" id="hdnTenantID" name="hdnTenantID" value="<?php echo $_SESSION['WEB_ADMIN_TENANTID']; ?>" />
                
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
                    	<td align="left">From Date &nbsp;&nbsp;&nbsp;&nbsp;Time<br />
                            <input type="text" name="txtHolidayFrom" value="" id="txtHolidayFrom" readonly/>
                            <!--<input type="text" name="txtHoliday" id="report_date"  onclick="return generateEnabled('btn_report_date');" readonly />-->
                        </td>
                            <td align="left">To Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Time<br />
                            <input type="text" name="txtHolidayTo" value="" id="txtHolidayTo" readonly/>
                            <!--<input type="text" name="txtHoliday" id="report_date"  onclick="return generateEnabled('btn_report_date');" readonly />-->
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
                            <input type="button" name="btnSave" id="btnSave" value="Save Holiday" onclick="onBtnSaveClick();"/>
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