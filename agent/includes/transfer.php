<?php
	session_start();
	require_once('config.php'); 
	require_once('errhandler.php');
	require_once('socket.php');
	require_once('serverresponse.php');
	
	if ( isset($_SESSION['WEB_AGENT_SESSION']) ) {
		$bSuccess = false;
		try {
			while ( $bSuccess == false ) {
				$socket = new ClientSocket(SERVER_IP,SERVER_PORT);
				
				if ( $socket->connect() ) {
					$szResponse = "";
					
					if ( isset( $_GET['exten'] ) ) {
						$serverResponse = new ServerResponse($socket->sendMessage('<transfer><session>' . $_SESSION['WEB_AGENT_SESSION'] .
							'</session><exten>' . $_GET['exten'] . '</exten></transfer>'));
						
						$szResponse = $serverResponse->getResponseObject();
					} else if ( isset($_GET['agent']) ) {
						$serverResponse = new ServerResponse($socket->sendMessage('<transfer><session>' . $_SESSION['WEB_AGENT_SESSION'] .
							'</session><agent>' . $_GET['agent'] . '</agent></transfer>'));
						
						$szResponse = $serverResponse->getResponseObject();
					}
					
					$bSuccess = true;
					echo $szResponse;
				} 
			}
		} catch (Exception $e ) {
		}
	} else {
		echo 'keepalive:0:';
	}
?>