<?php
	session_start();
	require_once('config.php'); 
	require_once('errhandler.php');
	require_once('classagentutil.php');
	
	if ( isset($_SESSION['WEB_AGENT_SESSION']) ) {
		if ( isset( $_GET['agentrequest'] ) ) {
			if ( $_GET['agentrequest'] == 'getwrapuplist' ) {
				$agentUtil = new AgentUtil();
				
				echo 'getwrapuplist:1:' . $agentUtil->getWrapupList( $_GET['tenantid'], $_GET['queue'] );
			}
		}
	} else {
		echo 'keepalive:0:';
	}
?>