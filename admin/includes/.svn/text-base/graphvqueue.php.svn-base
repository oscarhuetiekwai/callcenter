<?php
	session_start();
	require_once('config.php'); 
	require_once('errhandler.php');
	include('phpgraph/phpgraphlib.php');
	include('phpgraph/phpgraphlib_pie.php');
	
	if ( isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		if (isset($_SESSION['WEB_ADMIN_VQUEUE_GRAPH']) ) {
			$arrQueue = explode("|", $_SESSION['WEB_ADMIN_VQUEUE_GRAPH']);
			$arrTemp = array();
			
			foreach( $arrQueue as $queue ) {
				if ( $queue != '' ) {
					$arrKeyPair = explode(":", $queue );
					
					//array_push($arrTemp[$arrKeyPair[0]], $arrKeyPair[1] );
					$arrTemp[$arrKeyPair[0]] = $arrKeyPair[1];
				}
			}
			
			$graph = new PHPGraphLibPie(350, 200);
			$graph->addData($arrTemp);
			$graph->setTitle('Total Calls Received');
			$graph->setLabelTextColor('50,50,50');
			$graph->setLegendTextColor('50,50,50');
			$graph->createGraph();
		}
	}
?>