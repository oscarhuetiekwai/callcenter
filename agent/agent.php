<?php
    session_start();
	require_once('includes/config.php');
	require_once('includes/errhandler.php');
	require_once('includes/classpagecommon.php');
	require_once('includes/classagentutil.php');

	if ( !isset($_SESSION['WEB_AGENT_SESSION']) ) {
		header('location:login.php');
	}

	$pageCommon = new PageCommon();
	$agentUtil = new AgentUtil();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height: 100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Accordia Solution - Agent</title>
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" href="css/style.css" />
<script language="javascript" type="text/javascript" src="javascript/jquery/jquery-1.5.1.js"></script>
<script language="javascript" type="text/javascript" src="javascript/chat/chatCIS.js"></script>
<script language="javascript" type="text/javascript" src="javascript/prototype.js"></script>
<script language="javascript" type="text/javascript" src="javascript/effects.js"></script>
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/keepalive.js"></script>
<script language="javascript" type="text/javascript">

	function onDispoClick()
	{
		if(document.getElementById("successful").checked)
		{
			document.getElementById("date1").style.display = "none";
		}
		else if(document.getElementById("unreachable").checked)
		{
			document.getElementById("date1").style.display = "none";
		}
		else if(document.getElementById("noanswer").checked)
		{
			document.getElementById("date1").style.display = "none";
		}
		else if(document.getElementById("voicemail").checked)
		{
			document.getElementById("date1").style.display = "none";
		}
		else
		{
			document.getElementById("date1").style.display = "inline";
		}
	}

	function SaveDisposition()
	{
		var callid = document.getElementById("hdncallid").value;
		var disposition = "";

			if(document.getElementById("successful").checked)
				{
				   disposition = document.getElementById("successful").value;
				}
			else if(document.getElementById("unreachable").checked)
				{
					disposition = document.getElementById("unreachable").value;
				}
			else if(document.getElementById("noanswer").checked)
				{
					disposition = document.getElementById("noanswer").value;
				}
			else if(document.getElementById("voicemail").checked)
				{
					disposition = document.getElementById("voicemail").value;
				}
			else if(document.getElementById("callback").checked)
				{
					disposition = document.getElementById("callback").value;
				}
			else
				{
					disposition = "";
				}

		if(callid != "")
			{
				if(disposition != "")
					{
						addHttpRequest("campaigndisposave","callid=" + callid + "&disposition=" + disposition);
					}
			}
	}

        function RejectCampaignCall(){
		var campaignid = document.getElementById("campaignid").value;
		var customerid = document.getElementById("customerid").value;
		var campaign = document.getElementById("campaign").value;

		addHttpRequest("campaignreject","campaignid=" + campaignid + "&customerid=" + customerid + "&campaign=" + campaign);

                closeOffersDialog('boxpopup');
                clearbox();
	}

        function DoneCall()
        {
            var donebuttonvisibility = document.getElementById("dispo").style.visibility;
            var checkcall = "true";

            if(donebuttonvisibility == "visible"){
                if(document.getElementById("successful").checked)
                    {
                       checkcall = "true";
                    }
                else if(document.getElementById("unreachable").checked)
                    {
                        checkcall = "true";
                    }
                else if(document.getElementById("noanswer").checked)
                    {
                        checkcall = "true";
                    }
                else if(document.getElementById("voicemail").checked)
                    {
                        checkcall = "true";
                    }
                else if(document.getElementById("callback").checked)
                    {
                        if(document.getElementById("date1").value == ""){
                            checkcall = "false";
                            alert("Please select callback date and time.");
                        }
                        else
                            {
                        checkcall = "true";}
                    }
                else
                    {
                        checkcall="false";
                        alert("Please select DISPOSITION first before clicking DONE.");
                    }
            }
            else
                {
                    checkcall = "true";
                }
            if(checkcall=="true"){
                SaveDisposition();
                var campaignid = document.getElementById("campaignid").value;
		var customerid = document.getElementById("customerid").value;
		var campaign = document.getElementById("campaign").value;

                if(document.getElementById("callback").checked)
                    {
                        var date1 = document.getElementById("date1").value;
                        //alert("campaignid=" + campaignid + "&customerid=" + customerid + "&campaign=" + campaign + "&callback" + date1);
                        addHttpRequest("campaigndone","campaignid=" + campaignid + "&customerid=" + customerid + "&campaign=" + campaign + "&callback=" + date1);

                    }else
                    {
                        addHttpRequest("campaigndone","campaignid=" + campaignid + "&customerid=" + customerid + "&campaign=" + campaign);
                    }
                closeOffersDialog('boxpopup');
                clearbox();

            }
        }



	function AcceptCampaignCall(phone){
            var donebuttonvisibility = document.getElementById("dispo").style.visibility;
            var checkcall = "true";


            if(donebuttonvisibility == "visible"){
                if(document.getElementById("successful").checked)
                    {
                       checkcall = "true";
                       SaveDisposition();
                    }
                else if(document.getElementById("unreachable").checked)
                    {
                        checkcall = "true";
                        SaveDisposition();
                    }
                else if(document.getElementById("noanswer").checked)
                    {
                        checkcall = "true";
                        SaveDisposition();
                    }
                else if(document.getElementById("voicemail").checked)
                    {
                        checkcall = "true";
                        SaveDisposition();
                    }
                else if(document.getElementById("callback").checked)
                    {
                        checkcall = "false";
                        alert("Please select other disposition if you want to make another call.");
                    }
                else
                    {
                        checkcall="false";
                        //var noty = noty({text: 'noty - a jquery notification library!'});
                        alert("Please select DISPOSITION first before making a CALL.");
                    }
            }
            else
                {
                    checkcall = "true";
                }
            if(checkcall=="true"){
                var phonenumber = "";
		var campaignid = document.getElementById("campaignid").value;
		var customerid = document.getElementById("customerid").value;
		var campaign = document.getElementById("campaign").value;

                cleardisposition();

                if(phone=='1') {
			phonenumber = document.getElementById("txtphone1").value;
		} else if(phone=='2') {
			phonenumber = document.getElementById("txtphone2").value;
		} else {
			//phonenumber = document.getElementById("txtphone3").value;
		}

		if(phonenumber == "" ) {
			alert("Please select phone number with value.");
		} else {
			addHttpRequest("campaigncall","phone=" + phonenumber + "&campaignid=" + campaignid + "&customerid=" + customerid + "&campaign=" + campaign);
			addHttpRequest("wrapupdetails","wrapupdetails=campaign&queueid=" + campaignid);
                        closeOffersDialog('boxpopup');
		}

            }
	}

        function cleardisposition()
        {
            document.getElementById("successful").checked = false;
            document.getElementById("unreachable").checked = false;
            document.getElementById("voicemail").checked = false;
            document.getElementById("callback").checked = false;
            document.getElementById("noanswer").checked = false;
            document.getElementById("date1").value = "";
        }

	function clearbox() {
		document.getElementById("customerid").value = "";
		document.getElementById("campaignid").value = "";
		document.getElementById("txtphone1").value = "";
		document.getElementById("txtphone2").value = "";
		//document.getElementById("txtphone3").value = "";
		document.getElementById("campaign").value = "";
	}


	function onUserStatusChange() {
		var seluserstatus = document.getElementById("seluserstatus");

		if ( seluserstatus ) {
			addHttpRequest("statuschange", "code=" + seluserstatus.value);
		}
	}

	function onTransfer() {
		var divtransfercontrol = document.getElementById("divtransfercontrol");

		if ( divtransfercontrol ) {
			if ( document.getElementById("selonlineagents").value != "" ) {
				addHttpRequest("getagentstatus", "username=" + document.getElementById("selonlineagents").value);
			}
			if ( divtransfercontrol.style.display == "none" ) {
				var effectTransferControl = new Effect.toggle('divtransfercontrol', 'appear');
			}
		}
	}

	function onselonlineagentsChange() {
		if ( document.getElementById("selonlineagents").value != "" ) {
			addHttpRequest("getagentstatus", "username=" + document.getElementById("selonlineagents").value);
		}
	}

	function onTransferAgent() {
		if ( document.getElementById("selonlineagents").value != "" ) {
			addHttpRequest("transfer", "agent=" + document.getElementById("selonlineagents").value);
		}
	}

	function onTransferNumber() {
		if ( document.getElementById("txttransferext").value != "" ) {
			addHttpRequest("transfer", "exten=" + document.getElementById("txttransferext").value);
		}
	}

	function onEndCall() {
		addHttpRequest("endcall","");
	}

	function makeOutCall() {
		var txtoutcallphone = document.getElementById("txtoutcallphone");

		if ( txtoutcallphone ) {
			if ( txtoutcallphone.value != "" ) {
				outCall(document.getElementById('txtoutcallphone').value);
				//var divoutcall = document.getElementById("divoutcall");

				//if ( divoutcall.style.display != "none" ) {
				//	var effectOutCall = new Effect.toggle('divoutcall', 'appear');
				//}

			} else {
				alert("Please input the phone number to be called to!!!");
			}
		}
	}

	function makeOutCallFrame(phone) {
		outCall( phone );
		var divoutcall = document.getElementById("divoutcall");

		if ( divoutcall.style.display != "none" ) {
			var effectOutCall = new Effect.toggle('divoutcall', 'appear');
		}
	}

	function onHold() {
		addHttpRequest("hold", "");
	}

	function displayFrame(frameName) {
		var  divframecontacts = document.getElementById("divframecontacts");
		var  divframeknowledgebase = document.getElementById("divframeknowledgebase");

		if ( frameName == "divframecontacts" ) {
			divframecontacts.style.display = "block";
		} else {
			divframecontacts.style.display = "none";
		}

		if ( frameName == "divframeknowledgebase" ) {
			divframeknowledgebase.style.display = "block";
		} else {
			divframeknowledgebase.style.display = "none";
		}
	}

	function toggleQueue() {
		var effectDivQueue = new Effect.toggle('divqueue', 'blind');
	}

        function getFrameHeight() {
                return ( document.height - 120 ) + "px";
        }

	function adjustHeight() {
		document.getElementById('colCRM').style.height=getFrameHeight();
                document.getElementById('frameCRM').height=getFrameHeight();
	}

        function onLoad() {

        jQuery('#date1').datetimepicker( {
		showSecond: true,
		dateFormat: 'yy-mm-dd',
		timeFormat: 'hh:mm:ss'
		} );
	  }

</script>
</head>

<body onload="assignUserName('<?php echo $_SESSION['WEB_AGENT_USER'] ?>'); startKeepAlive();onLoad();return false;" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" padding="0" style="height: 100%;">
<?php
	$pageCommon->displayHeader($_SESSION['WEB_AGENT_FNAME'],$_SESSION['WEB_AGENT_EXTEN'],$_SESSION['WEB_AGENT_TENANT']);
?>
<input type="hidden" id="hdnagenttenantid" value="<?php echo $_SESSION['WEB_AGENT_TENANTID']; ?>" />
<input type="hidden" id="htnagentusername" value="<?php echo $_SESSION['WEB_AGENT_USER'];?>" />
<input type="hidden" id="outboundoldstatus" value=""/>
<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="278" align="center" background="images/tile_blackbar.gif">

        <font color="#FFFFFF" face="Arial, Helvetica, sans-serif" size="2">Interaction Panel</font>
    </td>
    <td width="160" background="images/tile_blackbar.gif" valign="middle">
    	<input type="image" src="images/btn_sect_contacts.png" onclick="displayFrame('divframecontacts');"/>
    </td>
    <td width="160" background="images/tile_blackbar.gif" valign="middle">
    	<!-- <input type="image" src="images/btn_sect_knowledgebase.png" onclick="displayFrame('divframeknowledgebase');" /> -->
    </td>
    <!--<td width="160" height="38" background="images/tile_blackbar.gif" valign="middle">
    	<input id="btnqueue" type="image" src="images/btn_sect_queues.png" onclick="toggleQueue();" style="display:none;" />
    </td>-->
    <td background="images/tile_blackbar.gif" align="right">
    	<a href="login.php"><img src="images/btnlogout.png" /></a>&nbsp;&nbsp;</td>
  </tr>
</table>


<table width="100%" style="height: 90%;" border="0"  cellspacing="0" cellpadding="0">
  <tr>
    <td  class="tdcallinfo" width="20%" align="center" valign="top" bgcolor="#e2e9db">
        <div id="divqueueborder" style="width:99%">
        	<!--<table cellpadding="0" cellspacing="0" width="100%" style="background-color: #a8df7e;-moz-border-radius: 10px;-webkit-border-radius: 5px; padding: 0px; width:100%; margin:0 3%; height:100%; vertical-align:top;">-->
            <table cellpadding="0" cellspacing="0" width="100%" style="background-color: #a8df7e; padding: 0px; width:100%; margin:0 3%; height:100%; vertical-align:top;">
            	<tr align="center">
                	<th width="25%" style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px;">Caller ID</th>
                    <th width="52%" style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px;">Queue</th>
                    <th width="23%" style="text-align:center;font-family:Arial, Helvetica, sans-serif; font-weight:normal; font-size:11px;">Duration</th>
                </tr>
                <tr>
                	<td colspan="3">
                    	<div id="divqueue" style="overflow:auto; height:62px; width:100%; vertical-align:top;">
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <br />
    	<table width="95%">
        	<tr>
     			<td width="55">Status </td>
				<td><?php echo $agentUtil->getUserStatusList($_SESSION['WEB_AGENT_TENANTID']); ?>

                                </td>
            </tr>
        </table>

        <div id="divoutcall">
        	<table width="95%" bgcolor="#e6f0f1">
            	<tr>
                	<td colspan="3">
                    	<font size="-2" color="#FF0000"><b>Manual Call</b></font>
                    </td>
                </tr><tr>
                	<td>Phone</td>
                    <td width="130"><input type="text" id="txtoutcallphone" style="width: 130px;" /></td>
                    <td>
                    	<!-- <a onclick="makeOutCall();"><img src="images/btncall.png" /></a> -->
                        <input type="image" onclick="makeOutCall();" src="images/btncall.png" />
						<!--<input type="button" onclick="openOffersDialog();"  /> -->
                    </td>
                </tr>
            </table>
        </div>

        <div id="divcallcontrol" style="display:none;">
        	<table width="95%" bgcolor="#e6f0f1" cellspacing="0" cellpadding="0">
            	<tr>
                	<td colspan="2"><font size="-2" color="#FF0000"><b>Call Control</b></font></td>
                </tr>
            	<tr>
                	<td align="right">
                    	<!-- <a onclick="onTransfer();"><img alt="Transfer" src="images/btntransfer.png"/></a> -->
                        <input type="image" onclick="onTransfer();" src="images/btntransfer.png" />
                    </td>
                    <!--<td><a onclick="onHold();"><img alt="Hold" src="images/btnhold.png" /></a></td>-->
                    <td align="left">
                    	<input type="image" onclick="onEndCall();" src="images/btnendcall.png" />
						<!--<a onclick="onEndCall();"><img alt="End Call" src="images/btnendcall.png" /></a>-->
                    </td>
                </tr>
            </table>
        </div>

        <div id="divtransfercontrol" style="display: none;">
        	<table width="95%" bgcolor="#e6f0f1" cellspacing="0" cellpadding="0">
            	<tr>
                	<td>Number</td>
                    <td><input type="text" id="txttransferext" style="width:130px;" /></td>
                    <td><input type="image" src="images/btncall.png" onclick="onTransferNumber();"/></td>
                </tr>
                <tr>
                	<td>Agent</td>
                    <td><div id="divtransferagent"></div></td>
                    <td><input type="image" src="images/btncall.png" onclick="onTransferAgent();"/></td>
                </tr>
            </table>
        </div>
        <br/>

    	<div id="divcallinfo" style="display:none;">
        	<input type="hidden" id="hdncallid" value="" />
        	<table width="95%"  style="background-color:#e6f0f1;">
            	<tr>
                	<td width="100">Queue Type</td>
                    <td><div id="lblcampaigntype"></div></td>
                </tr>
                <tr>
                	<td>DNIS</td>
                    <td id="lbldnis"></td>
                </tr>
                <tr>
                	<td>Queue</td>
                    <td id="lblqueue"></td>
                </tr>
                <tr>
                	<td>Caller No.</td>
                    <td id="lblcallerno"></td>
                </tr>
            </table>
        </div>
        <br />
        <div id="divwrapup1" style="background-color:#e6f0f1; text-align:left; width: 95%; clear: both; display: none;">
        	<font size="-2" color="#FF0000"><b>Wrapups</b></font><br />
            <div id="divwrapups" style="overflow:auto; height:300px;">
            </div>
        </div>
    </td>
    <td width="70%" valign="top" bgcolor="#e6f0f1">
		<div id="divframecontacts2" >
			<iframe id="frameCRM"  name="frameCRM"
				src="../customers/login.php?acc_user=<?php echo $_SESSION['WEB_AGENT_USER'];?>&acc_pwd=<?php echo $_SESSION['WEB_AGENT_PWD'];?>" style="width:100%; height:500px;" frameborder="0"></iframe>
		</div>
	</td>
  </tr>
 </table>
<!-- start chat -->
<?php include("chat/chat.php"); ?>
<!-- end chat -->
</body>
</html>
<?php include("chat/javascript.php"); ?>