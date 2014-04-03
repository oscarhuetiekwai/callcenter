<?php
//no  cache headers 
header("Expires: Mon, 26 Jul 2012 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<?php
	session_start();
	require_once('config.php'); 
	require_once('errhandler.php');
	require_once('socket.php');
	require_once('serverresponse.php');
	
	if ( isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		if ( isset($_GET['license']) ) {
			if ( $_GET['license'] == 'info') {
				$socket = new ClientSocket(SERVER_IP,SERVER_PORT);
			
				if ( $socket->connect() ) {
					$serverResponse = new ServerResponse($socket->sendMessage('<license><type>info</type></license>'));
						
					echo $serverResponse->getResponseObject();	
				} else {
					echo 'license:info:-1:Unable to connect to the server!!!';
					//throw new Exception("Unable to connect to the server!!!!");
				} 
			} else if ( $_GET['license'] == 'update' ) {
				$socket = new ClientSocket(SERVER_IP,SERVER_PORT);
			
				if ( $socket->connect() ) {
					$serverResponse = new ServerResponse($socket->sendMessage('<license><type>update</type><key>' . 
						$_GET['key'] . '</key></license>'));
						
					echo $serverResponse->getResponseObject();	
				} else {
					echo 'license:update:-1:Unable to connect to the server!!!';
				}
			}
		} else if ( isset($_GET['changepwd']) ) {
			$mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
			$nReturn = 0;
			
			if ( $mySQL ) {
				$query = "SELECT userpass FROM users WHERE userid=" . $_SESSION['WEB_ADMIN_USERID'];
					
				$resultSet = $mySQL->query( $query );
				
				if ( $resultSet ) {
					$objRow = $resultSet->fetch_object();
					
					if ( strcmp( $objRow->userpass, $_GET["oldpass"] ) == 0 ) {
						$query = "UPDATE users SET userpass='" . $_GET["newpass"] . "' WHERE userid=" . $_SESSION['WEB_ADMIN_USERID'];
						$resultSet = $mySQL->query( $query ); 
						$nReturn = 1;
					}
					
				}
				
				
				$mySQL->close();
			}
			
			echo 'changepwd:' . $nReturn . ':';
		}
	}
?>