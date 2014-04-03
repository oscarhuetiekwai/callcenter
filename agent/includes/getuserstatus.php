<?php
	session_start();
	require_once('config.php'); 
	require_once('errhandler.php');
	require_once('classagentutil.php');
	
	if ( isset($_SESSION['WEB_AGENT_SESSION']) ) {
                $agentUtil = new AgentUtil();

                echo 'getuserstatus:1:' .  $_SESSION['WEB_AGENT_USER'] . ':' . $agentUtil->getUserStatusID($_SESSION['WEB_AGENT_USER']);
	} else {
		echo 'keepalive:0:';
	}
?>