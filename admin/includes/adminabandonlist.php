	<script>
	function goToThisPage(j)
	{
	var sortByFil = document.getElementById("sortByFil");
	var calleridFil = document.getElementById("calleridFil");
	var queueNameFil = document.getElementById("queueNameFil");
	var dateFil = document.getElementById("dateFil");
        
        document.getElementById("onJsFunction").innerHTML="goToThisPage"; 
        document.getElementById("pageNumber").innerHTML=j; 
	
	$('#queuelogsDivMain').load('includes/adminabandonlist.php?p='+j+'&queueNameFil='+queueNameFil.value+'&dateFil='+dateFil.value+'&sortByFil='+sortByFil.value+'&calleridFil='+calleridFil.value);
	}
	
	function reloadList()
	{
	var sortByFil = document.getElementById("sortByFil");
	var calleridFil = document.getElementById("calleridFil");
	var queueNameFil = document.getElementById("queueNameFil");
	var dateFil = document.getElementById("dateFil");
	
	$('#queuelogsDivMain').load('includes/adminabandonlist.php?queueNameFil='+queueNameFil.value+'&dateFil='+dateFil.value+'&sortByFil='+sortByFil.value+'&calleridFil='+calleridFil.value);
	}
	
	</script>

	
	<?php
	/*
	if($_GET['dateFil'] == ''){
	$_GET['dateFil'] = 'ALL TIME';
	}
	else{
	}
	*/
	?>
	
	<div style="display:inline-block;width:607px;">
	<b>Date :</b> <?php echo $_GET['dateFil']; ?>
	<img src='images/Refresh.png' title="Refresh List" width="20px" height="20px" style="vertical-align:middle;cursor:pointer;float:right;" onclick="reloadList();">
	</div>
	
	<br>
	<table class='queuelogs_table' style="z-index:0;width:607px;margin-left:-3px;">
	<tr class="queuelogsTr"><td width=200px style="border-top-left-radius:5px;">Caller ID</td><td style="width:200px;">Queue Name</td><td style="width:80px;">Time In Queue</td><td style="width:80px;">Abandon Time</td>
	<td style="width:100px;border-top-right-radius:5px;">Queue Duration(s)</td>
	</tr>
	</table>
	<div class="queuelogsDiv" style="width:600px;height:300px;overflow:auto;">
	<?php //echo $_GET['dateFil']; echo $_GET['queueNameFil']; echo $_GET['calleridFil']; echo $_GET['sortByFil'];?>
	

		<table class='queuelogs_table' style="z-index:0;">
		<tr class="queuelogsTr" style="visibility:hidden;"><td width=200px>Caller ID</td><td style="width:200px;">Queue Name</td><td style="width:80px;">Time In Queue</td><td style="width:80px;">Abandon Time</td>
		<td style="width:100px;">Queue In Time</td>
		</tr>
			<?php
		
				include('config2.php');
				
					if($_GET['p'] == ''){
					$from = 0;
					}
					else{
					$from = $_GET['p'];
					}
					
					if($_GET['sortByFil'] == ''){
					$_GET['sortByFil'] = 'SUBSTRING(queuetime,12,8)';
					}
					else{
					}
					
					if($_GET['dateFil'] == ''){
					$_GET['dateFil'] = '';
					}
					else{
					}
				
				///////////count pages/////////////////
				
				if($_GET['queueNameFil'] =='' || $_GET['queueNameFil'] =='ALL')
				{
				
				//SELECT callerid,queuename,queuetime,disconnecttime AS abandontime,queueduration FROM reportcallcenter.callcontactsdetails WHERE lastevent = 'ABANDON'
				
					$select = "SELECT callerid,queuename,queuetime,disconnecttime AS abandontime,queueduration FROM reportcallcenter.callcontactsdetails WHERE lastevent = 'ABANDON' AND SUBSTRING(queuetime,1,10) LIKE '".$_GET['dateFil']."' AND callerid LIKE '%".$_GET['calleridFil']."%' ORDER BY ".$_GET['sortByFil']." ASC";

				}
				else
				{
					$select = "SELECT callerid,queuename,queuetime,disconnecttime AS abandontime,queueduration FROM reportcallcenter.callcontactsdetails WHERE lastevent = 'ABANDON' AND queuename = '".$_GET['queueNameFil']."' AND SUBSTRING(queuetime,1,10) LIKE '".$_GET['dateFil']."' AND callerid LIKE '%".$_GET['calleridFil']."%' ORDER BY ".$_GET['sortByFil']." ASC";
				}

				$query = mysql_query($select);
				$total_data = mysql_num_rows($query);
				
				$step = 200;
				

				
				
				
				/////display ///list//////////

				if($_GET['queueNameFil'] =='' || $_GET['queueNameFil'] =='ALL')
				{	
					$select = "SELECT callerid,queuename,queuetime,disconnecttime AS abandontime,queueduration FROM reportcallcenter.callcontactsdetails WHERE lastevent = 'ABANDON' AND SUBSTRING(queuetime,1,10) LIKE '".$_GET['dateFil']."' AND callerid LIKE '%".$_GET['calleridFil']."%' ORDER BY ".$_GET['sortByFil']." ASC LIMIT ".$from.",".$step."";
				}
				else
				{	
	
					$select = "SELECT callerid,queuename,queuetime,disconnecttime AS abandontime,queueduration FROM reportcallcenter.callcontactsdetails WHERE lastevent = 'ABANDON' AND queuename = '".$_GET['queueNameFil']."' AND SUBSTRING(queuetime,1,10) LIKE '".$_GET['dateFil']."' AND callerid LIKE '%".$_GET['calleridFil']."%' ORDER BY ".$_GET['sortByFil']." ASC LIMIT ".$from.",".$step."";
				}
				
				$query = mysql_query($select);
				

				while($row = mysql_fetch_array($query))
				{
				extract($row);
				$justAbandonTime = substr($abandontime,strpos($abandontime,' '));
				$justTimeInQueue = substr($queuetime,strpos($queuetime,' '));
				
				echo "<tr><td>".$callerid."</td><td>".$queuename."</td><td>".$justTimeInQueue."</td><td>".$justAbandonTime."</td><td>".$queueduration."</td></tr>";
				}
			?>
		</table>

	</div>
<br>

<?php
$p=1;

echo '<div style="width:700px;">';

for ($j = 0 ; $j <= $total_data+$step; $j+=$step) {
    echo '<a onclick="goToThisPage('.$j.');" style="cursor:pointer;" class="pagesLink">'.$p.'</a>'; 
    $p++;
}
echo '</div>';
			/*
			for ($j = 0 ; $j <= $total_data+$step; $j+=$step) 
			{ 
				echo ' <a href="index.php?p='.$j.'&queueNameFil='.$_GET['queueNameFil'].'" class="pagesLink">'.$p.'</a> '; 
				$p++;

			} 
			*/

?>








