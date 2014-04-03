<?php
	session_start();
	require_once('config.php'); 
	require_once('errhandler.php');
	require_once('classagentutil.php');
	
	if ( isset($_SESSION['WEB_AGENT_SESSION']) ) {
		if (  isset($_GET['username'] ) ) {
			$agentUtil = new AgentUtil();
		
			echo 'getagentstatus:1:' .  $_GET['username'] . ':' . $agentUtil->getAgentStatus( $_GET['username']);
		}
	} else {
		echo 'keepalive:0:';
	}
?>