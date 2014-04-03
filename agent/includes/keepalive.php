<?php
	session_start();
	require_once('config.php'); 
	require_once('errhandler.php');
	require_once('socket.php');
	require_once('serverresponse.php');
	
	if ( isset($_SESSION['WEB_AGENT_SESSION']) ) {
		$bSuccess = false;
		
		while ( $bSuccess == false ) {
			try {
				$socket = new ClientSocket(SERVER_IP,SERVER_PORT);
				
				if ( $socket->connect() ) {
					$serverResponse = new ServerResponse($socket->sendMessage('<keepalive><uid>' .
						$_SESSION['WEB_AGENT_USER'] . '</uid><session>' . $_SESSION['WEB_AGENT_SESSION'] .
						'</session></keepalive>'));
					
					echo $serverResponse->getResponseObject();
					$bSuccess = true;
				} 
			} catch ( Exception $e ) {
			}
		}
	} else {
		echo 'keepalive:0:';
	}
?>