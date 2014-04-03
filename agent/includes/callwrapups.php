<?php
	session_start();
	require_once('config.php'); 
	require_once('errhandler.php');
	require_once('classagentutil.php');

	if ( isset($_SESSION['WEB_AGENT_SESSION']) ) {
		$agentUtil = new AgentUtil();
		
		$agentUtil->saveCallWrapups( $_GET['callid'], $_GET['wrapups'] );
		
		echo 'keepalive:0:';
	} 
?>