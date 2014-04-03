<?php
	session_start();
	require_once('config.php'); 
	require_once('errhandler.php');
	require_once('socket.php');
	require_once('serverresponse.php');
	
	if ( isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		$socket = new ClientSocket(SERVER_IP,SERVER_PORT);
		
		if ( $socket->connect() ) {
			$serverResponse = new ServerResponse($socket->sendMessage('<keepalive><uid>' .
				$_SESSION['WEB_ADMIN_USER'] . '</uid><session>' . $_SESSION['WEB_ADMIN_SESSION'] .
				'</session></keepalive>'));
			
			echo $serverResponse->getResponseObject();
			
		} else {
			throw new Exception("Unable to connect to the server!!!!");
		}
	} else {
		echo 'keepalive:0:';
	}
?>