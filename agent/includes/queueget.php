<?php
	session_start();
	require_once('config.php'); 
	require_once('errhandler.php');
	require_once('socket.php');
	require_once('serverresponse.php');
	
	if ( isset($_SESSION['WEB_AGENT_SESSION']) ) {
		$socket = new ClientSocket(SERVER_IP,SERVER_PORT);
		$szReturn = "";
		
		if ( $socket->connect() ) {
			$serverResponse = new ServerResponse($socket->sendMessage('<queueget><session>' . 
				$_SESSION['WEB_AGENT_SESSION'] . '</session></queueget>'));
			
			$szReturn = $serverResponse->getResponseObject();
			
			$mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
			
			if ( $mySQL ) {
				$query ="SELECT callerid, status, timestamp, queue, position, '' AS username, userexten, " .
					"SEC_TO_TIME( TIMESTAMPDIFF( SECOND, TIMESTAMP, CURRENT_TIMESTAMP) ) AS duration  FROM " .
					"callstatus WHERE queue='" . $_SESSION['WEB_AGENT_USER'] . "' AND STATUS='IN QUEUE' ORDER BY TIMESTAMP DESC;";
				
				$resultSet = $mySQL->query($query);
			
				$objRow = $resultSet->fetch_object();
			
				
				while ( $objRow ) {
					$szRow = $objRow->callerid;
					$szRow .= "<dquote />";
					$szRow .= $objRow->status;
					$szRow .= "<dquote />";
					$szRow .= str_replace(":", "<tcolon />", $objRow->timestamp);
					$szRow .= "<dquote />";
					$szRow .= $objRow->queue;
					$szRow .= "<dquote />";
					$szRow .= $objRow->position;
					$szRow .= "<dquote />";
					$szRow .= "0";
					$szRow .= "<dquote />";
					$szRow .= $objRow->username;
					$szRow .= "<dquote />";
					$szRow .= $objRow->userexten;
					$szRow .= "<dquote />";
					$szRow .= str_replace(":", "<tcolon />", $objRow->duration);
					
					if ( $szReturn == "" ) {
						$szReturn = $szRow;
					} else {
						$szReturn .= ( "|" . $szRow );
					}
					
					$objRow = $resultSet->fetch_object();
				}
					
				$mySQL->close();
			}
			
			
			echo 'queueget:1:' . $szReturn;
			
		} else {
			throw new Exception("Unable to connect to the server!!!!");
		}
	} else {
		echo 'queueget:0:';
	}
?>