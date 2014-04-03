<?php
	session_start();
	require_once('config.php'); 
	require_once('errhandler.php');
	require_once('socket.php');
	require_once('serverresponse.php');
	
	if ( isset($_SESSION['WEB_AGENT_SESSION']) ) {
		$socket = new ClientSocket(SERVER_IP,SERVER_PORT);
		
		if ( $socket->connect() ) {
			$serverResponse = new ServerResponse($socket->sendMessage('<holdcall><session>' . $_SESSION['WEB_AGENT_SESSION'] .
				'</session></holdcall>'));
			
			echo $serverResponse->getResponseObject();
			
		} else {
			throw new Exception("Unable to connect to the server!!!!");
		}
	} else {
		echo 'keepalive:0:';
	}
?>