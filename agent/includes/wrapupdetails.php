<?php
	session_start();
	require_once('config.php'); 
	require_once('errhandler.php');
	require_once('classagentutil.php');

	if ( isset($_SESSION['WEB_AGENT_SESSION']) ) {
		$agentUtil = new AgentUtil();
		
		if ( $_GET['wrapupdetails'] == 'inbound' ) {
			echo 'wrapupdetails:1:' . str_replace( ":", "<tcolon />" ,$agentUtil->getInboundWrapupList( $_GET['queueid'] ));
		} else {
		}
	} else {
		echo 'keepalive:0:';
	}
?>