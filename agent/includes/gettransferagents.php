<?php
	session_start();
	require_once('config.php'); 
	require_once('errhandler.php');
	require_once('classagentutil.php');
	
	if ( isset($_SESSION['WEB_AGENT_SESSION']) ) {
		$agentUtil = new AgentUtil();
		
		echo 'gettransferagents:1:' . str_replace(":", "<tcolon />" , $agentUtil->getAgentOnlineSelection( $_GET['tenantid'], $_GET['excludeuser'] ));
	} else {
		echo 'keepalive:0:';
	}
?>