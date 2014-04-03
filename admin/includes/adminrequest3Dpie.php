<?php
//no  cache headers 
header("Expires: Mon, 26 Jul 2012 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<?php
  // script name: get_latest_data.php
  // coder: Sony AK Knowledge Center - www.sony-ak.com
  // code updated on Feb 26, 2010
  session_start();
 

  
 
  //$index = $rowCount - 1;
  //for ($i=0;$i<$rowCount;$i++) {
  //	$callstatus = mysql_result($resultSet, $index, "callstatus");
  //	$callid = mysql_result($resultSet, $index, "callid");<br>
if ($_SESSION['GRAPH_QUEUE_CALLRECEIVED'] ==0){
	$xmlData ="<graph caption='Call Percentage' pieSliceDepth='30' showBorder='1' numberSuffix='' showValues='0' showNames='1'>";
	$xmlData .= "<set name='No Call Yet' value='1'  />";
	
}else{
	$xmlData ="<graph caption='Call Status' pieSliceDepth='30' showBorder='1' showNames='1' formatNumberScale='0' numberSuffix=' Call' decimalPrecision='0'>";
  	$xmlData .= "<set name='Connected' value='" . $_SESSION['GRAPH_QUEUE_CONNECTED']  . "' color='00FF00' />";
	$xmlData .= "<set name='Queue' value='" . $_SESSION['GRAPH_QUEUE_QUEUES']  . "' color='FF0000' />";

	
}
 // 	$index--;
  //}
 
  $xmlData .= "</graph>";
 
  // insert new data, to make fluctuation effect on the chart
 // mysql_query("INSERT INTO price_fluctuation (callstatus) VALUES (now(), " . rand(1000, 5000) . ")", $dbConn);
 
  echo $xmlData;
?>
