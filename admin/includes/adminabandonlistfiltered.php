<?php 
session_start();

?>
<style>
body,table,div,input,a,span,font {
font-size:10pt;
font-family:sans-serif;
}

.queuelogs_table {
width:100%;
}
.queuelogs_table td {
border-style:solid;
border-width:1px;
border-color:grey;
padding-left:5px;
}
.queuelogsTr {
background-color:lightgrey;
}
</style>


		<table class='queuelogs_table'>
	<tr class="queuelogsTr"><td width=200px>Caller ID</td><td style="width:100px;">Queue Name</td><td style="width:200px;">Time In Queue</td><td style="width:100px;">Abandon Time</td>
		<td style="width:100px;">Queue In Time</td>
		</tr>
			<?php
		
					include('config2.php');
				
				if($_GET['queueNameFil'] =='' || $_GET['queueNameFil'] =='ALL')
				{	
					$select = "SELECT callerid,queuename,queuetime,disconnecttime AS abandontime,queueduration FROM reportcallcenter.callcontactsdetails WHERE lastevent = 'ABANDON' AND SUBSTRING(queuetime,1,10) LIKE '".$_GET['dateFil']."' AND callerid LIKE '%".$_GET['calleridFil']."%' ORDER BY ".$_GET['sortByFil']." ASC";
				}
				else
				{	
					$select = "SELECT callerid,queuename,queuetime,disconnecttime AS abandontime,queueduration FROM reportcallcenter.callcontactsdetails WHERE lastevent = 'ABANDON' AND queuename = '".$_GET['queueNameFil']."' AND SUBSTRING(queuetime,1,10) LIKE '".$_GET['dateFil']."' AND callerid LIKE '%".$_GET['calleridFil']."%' ORDER BY ".$_GET['sortByFil']." ASC";
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









