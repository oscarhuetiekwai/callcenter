<?php
 
	session_start();
	require_once('includes/config2.php');
	require_once('includes/classpagecommon.php');
	if ( !isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		header("Location:login.php");
	}
	$pageCommon = new PageCommon();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Abandon Calls</title>

<link rel="stylesheet" type="text/css" href="javascript/jqueryui/css/redmond/jquery-ui-1.8.4.custom.css" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script type="text/javascript" src="javascript/jquery.ui.core.js" ></script>
<script type="text/javascript" src="javascript/jquery.blockUI.js" ></script>
<script type="text/javascript" src="javascript/jquery.DOMwindow.js" ></script>
<script type="text/javascript" src="javascript/jquery.selectboxes.js" ></script>
<script type="text/javascript" src="javascript/jquery.datepick.js" ></script>
<script type="text/javascript" src="javascript/jquery.autocomplete.js" ></script>
<script type="text/javascript" src="javascript/jquery.autocomplete.min.js" ></script>
<script language="javascript" type="text/javascript" src="javascript/keepaliveadminabandonlist.js"></script>

<script src="javascript/jquery-1.9.1.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css" />
<script src="javascript/jquery-ui.js"></script>

<?php $pageCommon->displayScriptChangePwd(); ?>
<?php
	$pageCommon->displayHeader($_SESSION['WEB_ADMIN_FNAME'],$_SESSION['WEB_ADMIN_EXTEN'],$_SESSION['WEB_ADMIN_USERLEVEL'],$_SESSION['WEB_ADMIN_TENANT']); 
	$pageCommon->displayNavigation('qmonitor', 'abandon');
?>

<script>
function filterDefault()
{
    document.getElementById("onJsFunction").innerHTML="filterDefault"; 
    document.getElementById("pageNumber").innerHTML=0;
   //$('#queuelogsDivMain').load('includes/adminabandonlist.php');
}
function filterByQueueName()
{
var sortByFil = document.getElementById("sortByFil");
var calleridFil = document.getElementById("calleridFil");
var queueNameFil = document.getElementById("queueNameFil");
var dateFil = document.getElementById("dateFil");

document.getElementById("onJsFunction").innerHTML="filterByQueueName";
document.getElementById("pageNumber").innerHTML=0;
$('#queuelogsDivMain').load('includes/adminabandonlist.php?calleridFil='+calleridFil.value+'&queueNameFil='+queueNameFil.value+'&sortByFil='+sortByFil.value+'&dateFil='+dateFil.value);
}

function filterByCallerId()
{
var sortByFil = document.getElementById("sortByFil");
var calleridFil = document.getElementById("calleridFil");
var queueNameFil = document.getElementById("queueNameFil");
var dateFil = document.getElementById("dateFil");

document.getElementById("onJsFunction").innerHTML="filterByCallerId"; 
document.getElementById("pageNumber").innerHTML=0;
$('#queuelogsDivMain').load('includes/adminabandonlist.php?calleridFil='+calleridFil.value+'&queueNameFil='+queueNameFil.value+'&sortByFil='+sortByFil.value+'&dateFil='+dateFil.value);
}

function filterByDate()
{
var sortByFil = document.getElementById("sortByFil");
var calleridFil = document.getElementById("calleridFil");
var queueNameFil = document.getElementById("queueNameFil");
var dateFil = document.getElementById("dateFil");
document.getElementById("queueNameFil").disabled = false;
document.getElementById("sortByFil").disabled = false;

document.getElementById("onJsFunction").innerHTML="filterByDate"; 
document.getElementById("pageNumber").innerHTML=0;
$('#queuelogsDivMain').load('includes/adminabandonlist.php?calleridFil='+calleridFil.value+'&queueNameFil='+queueNameFil.value+'&sortByFil='+sortByFil.value+'&dateFil='+dateFil.value);
}

function sortTheList()
{
var sortByFil = document.getElementById("sortByFil");
var calleridFil = document.getElementById("calleridFil");
var queueNameFil = document.getElementById("queueNameFil");
var dateFil = document.getElementById("dateFil");

document.getElementById("onJsFunction").innerHTML="sortTheList"; 
document.getElementById("pageNumber").innerHTML=0;
$('#queuelogsDivMain').load('includes/adminabandonlist.php?calleridFil='+calleridFil.value+'&queueNameFil='+queueNameFil.value+'&sortByFil='+sortByFil.value+'&dateFil='+dateFil.value);
}

function popprintTheList()
{
document.getElementById("onJsFunction").innerHTML="popprintTheList"; 
document.getElementById("pageNumber").innerHTML=0;
window.open('includes/adminabandonlistpop.php','_foo','width=500px,height=500px,left=100px,top=100px');
}

$(function() {
$( "#dateFil" ).datepicker({onSelect: filterByDate, dateFormat: 'yy-mm-dd' });
});
</script>

<style>
body,table,div,input,a,span,font {
font-size:10pt;
font-family:sans-serif;
}
.pagetable {

}
.queuelogs_table {
width:100%;
}
.queuelogs_table td {
border-bottom:1px solid maroon;
padding-left:5px;
}

.queuelogsDiv {
border-style:solid;
border-color:maroon;
border-width:1px;
box-shadow:0px 2px 2px grey;
border-radius:5px;
position:relative;
z-index:2;
}

.queuelogsTr {
background-image:-webkit-linear-gradient(top,maroon,#a14444);
background-image:-moz-linear-gradient(top,maroon,#a14444);
background-image:-o-linear-gradient(top,maroon,#a14444);
border-style:none;
box-shadow:0px 2px 2px grey;
color:white;
font-size:8pt;
}


.queuelogsTrFloat {
background-image:-webkit-linear-gradient(top,maroon,#a14444);
background-image:-moz-linear-gradient(top,maroon,#a14444);
background-image:-o-linear-gradient(top,maroon,#a14444);
border-style:none;
box-shadow:0px 2px 2px grey;
color:white;
position:fixed;
width:450px;
margin:0px;
margin-top:-2px;
margin-left:-2px;
}

.titlePageDiv {
background-image:-webkit-linear-gradient(top left,cornflowerblue,lightblue);
background-image:-moz-linear-gradient(top left,cornflowerblue,lightblue);
background-image:-o-linear-gradient(top left,cornflowerblue,lightblue);
width:99%;
padding:5px;
color:white;
border-top-left-radius:5px;
border-top-right-radius:5px;
box-shadow:0px 2px 2px grey;
text-shadow:0px 2px 2px grey;
}

.pagesLink {
background-color:lightgrey;
border-style:solid;
border-width:1px;
border-color:grey;
display:inline-block;
margin:1px;
padding:2px;
}

.buttonstyle {
background-image:-webkit-linear-gradient(top left,maroon,maroon);
background-image:-moz-linear-gradient(top left,maroon,maroon);
background-image:-o-linear-gradient(top left,maroon,maroon);
border-style:none;
color:white;
border-radius:5px;
box-shadow:0px 2px 2px grey;
-webkit-transition:all .5s;
-moz-transition:all .5s;
-o-transition:all .5s;
cursor:pointer;
}

.buttonstyle:hover {
color:yellow;
}
</style>


</head>
<body onload="startKeepAlive();" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<!---<div class="titlePageDiv">ABANDON CALLS</div>---->
<table class="pagetable" cellspacing="15">
<tr valign="top"><td>
<h2>Search Criteria's</h2>
<br>
	<div class="queuelogsDiv" style="width:350px;padding:10px;">
	<table>
	<tr valign="top"><td>Filter by <br>
	</td>
	<td>
		<table style="width:100%;">
			<tr>
			<td>Queue Name
			<br>
			<select id="queueNameFil" onchange="filterByQueueName();" disabled="disabled">
			
			<?php
			$select = "SELECT queuename,queuenameinternal FROM tenantqueues";
			$query = mysql_query($select);

			echo "<option value='ALL'>All</option>";
			while($row = mysql_fetch_array($query))
			{
			extract($row);
			echo "<option value='".$queuenameinternal."'>".$queuename."</option>";
			}
			?>
			</select>
			<br>
			Caller ID
			<br>
			<input type="text" id="calleridFil" placeholder="Caller ID" onkeyup="filterByCallerId();">
			<!--<button onclick="filterByCallerId();" class="buttonstyle">Search</button>--><br>
			Date<br>
			<input type="text" id="dateFil" placeholder="Select Date">
			
			</td></tr>
			</table>
	</td>
	</tr>
	<tr><td>Sort by</td>
	<td>
	<select id="sortByFil" onchange="sortTheList();" disabled="disabled">
	<option value="SUBSTRING(queuetime,12,8)">Time In Queue</option>
	<option value='callerid'>Caller ID</option>
	<option value='queuename'>Queue Name</option>
	<option value="SUBSTRING(disconnecttime,12,8)">Abandon Time</option>
	<option value='queueduration'>Queue In Time</option>
	</select>
	</td>
	</tr>
	</table>
	<hr>
	<button class="buttonstyle" onclick="popprintTheList();">Print List</button>
	<br><br>
	</div>
</td>
<td>
<h2 style="display:inline-block;">List of Abandon Calls</h2>
<div id="queuelogsDivMain">
	<div style="display:inline-block;width:607px;">
	<img src='images/Refresh.png' title="Refresh List" width="20px" height="20px" style="vertical-align:middle;cursor:pointer;float:right;" onclick="reloadList();">
	</div>
	<table class='queuelogs_table' style="z-index:0;width:607px;margin-left:-3px;">
	<tr class="queuelogsTr"><td width=200px style="border-top-left-radius:5px;">Caller ID</td><td style="width:200px;">Queue Name</td><td style="width:80px;">Time In Queue</td><td style="width:80px;">Abandon Time</td>
	<td style="width:100px;border-top-right-radius:5px;">Queue In Time</td>
	</tr>
	</table>
	
	<div class="queuelogsDiv" style="width:600px;height:300px;overflow:auto;">
		<table class='queuelogs_table' style="z-index:0;">
		<tr class="queuelogsTr" style="visibility:hidden;"><td width=200px>Caller ID</td><td style="width:200px;">Queue Name</td><td style="width:80px;">Time In Queue</td><td style="width:80px;">Abandon Time</td>
		<td style="width:100px;">Queue In Time</td>
		</tr>
		</table>
	</div>

</div>
<div id="onJsFunction" style="visibility: hidden">filterDefault</div>
<div id="pageNumber" style="visibility: hidden"></div>

</td>
</tr>
</table>
<?php $pageCommon->displayDivChangePwd(); ?>
<?php $pageCommon->displayChatBoxList($_SESSION['WEB_ADMIN_SESSION'],$_SESSION['WEB_ADMIN_USER'],$_SESSION['WEB_ADMIN_USERID']); ?>
</body>
</html>









