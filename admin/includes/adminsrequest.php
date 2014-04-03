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
		if ( isset($_GET['statstype']) ) {
			if ( $_GET['statstype'] == 'callstatus' ) {
				$socket = new ClientSocket(SERVER_IP,SERVER_PORT);
		
				if ( $socket->connect() ) {
					$serverResponse = new ServerResponse($socket->sendMessage('<statistics><tenantid>' .
						$_GET['tenantid']. '</tenantid><statstype>' . $_GET['statstype'] .
						'</statstype></statistics>'));
					
					echo $serverResponse->getResponseObject();
					
				} else {
					throw new Exception("Unable to connect to the server!!!!");
				}
				
				echo 'adminstats:callstatus:1:' . $serverResponse->getRawResponse(); 
			} else if ( $_GET['statstype'] == 'userstatus' ) {
				$socket = new ClientSocket(SERVER_IP,SERVER_PORT);
		
				if ( $socket->connect() ) {
					$serverResponse = new ServerResponse($socket->sendMessage('<statistics><tenantid>' .
						$_GET['tenantid']. '</tenantid><statstype>' . $_GET['statstype'] .
						'</statstype></statistics>'));
					
					echo $serverResponse->getResponseObject();
					
				} else {
					throw new Exception("Unable to connect to the server!!!!");
				}
				
				echo 'adminstats:userstatus:1:' . $serverResponse->getRawResponse(); 
			} else if ( $_GET['statstype'] == 'queues' ) {
				$mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
				$szReturn = '<table>';
				$nConnected = 0;
				$nQueue = 0;
				
				if ( $mySQL ) {
					$query = "SELECT dnis, tq.queuename AS queuename, callerid, callername, callstatus, SEC_TO_TIME( TIMESTAMPDIFF( SECOND, statustimestamp, CURRENT_TIMESTAMP ) ) AS duration, agentname, agentid, agentext  FROM rtqueue rq INNER JOIN tenantqueues tq ON tq.queuenameinternal=rq.queue " .
						"WHERE tq.tenantid=" . $_GET['tenantid'] . ";";
						
					$resultSet = $mySQL->query( $query );
					
					if ( $resultSet ) {
						$objRow = $resultSet->fetch_object();
						$bToggle = true;
						
						while ( $objRow ) {
							$szReturn .= '<tr bgcolor="' . ( $bToggle ? "#f8f8f8" : "#f0f0f0" ) . '">';
							$szReturn .= ( '<td style="width: 60px;">' . $objRow->dnis . '</td>' );
							$szReturn .= ( '<td style="width: 120px;text-align: center;">' . $objRow->queuename . '</td>' );
							$szReturn .= ( '<td style="width: 100px;text-align: center;">' . $objRow->callerid . '</td>' );
							$szReturn .= ( '<td style="width: 100px;text-align: center;">' . $objRow->callername . '</td>' );
							$szReturn .= ( '<td style="width: 80px;text-align: center;">' . $objRow->callstatus . '</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->duration . '</td>' );
							$szReturn .= ( '<td style="width: 100px;text-align: center;">' . $objRow->agentname . '</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->agentid . '</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->agentext . '</td>' );
							$szReturn .= '</tr>';
							
							if ( $objRow->callstatus == "QUEUE" ) {
								$nQueue++;
							} else {
								$nConnected++;
							}
							
							$objRow = $resultSet->fetch_object();
							$bToggle = ( $bToggle ? false : true );
						}
						
					}
					
					
					$mySQL->close();
				}
				
				$szReturn .= '</table>';
					
				$szReturn = str_replace( ":", "<tcolon />", $szReturn);
					
				echo 'adminstats:queues:1:' . $nConnected . ':' . $nQueue . ':' . $szReturn; 
			} else if ( $_GET['statstype'] == 'queues1' ) { /* this is for json */
				$mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
				//$szReturn = '<table>';
				$nTotalRows = 0;
				
				
				$rows = array();
				
				if ( $mySQL ) {
					$query = "SELECT dnis, tq.queuename AS queuename, callerid, callername, callstatus, SEC_TO_TIME( TIMESTAMPDIFF( SECOND, statustimestamp, CURRENT_TIMESTAMP ) ) AS duration, agentname, agentid, agentext  FROM rtqueue rq INNER JOIN tenantqueues tq ON tq.queuenameinternal=rq.queue " .
						"WHERE tq.tenantid=" . $_GET['tenantid'] . ";";
						
					$resultSet = $mySQL->query( $query );
					
					
					if ( $resultSet ) {
						$objRow = $resultSet->fetch_object();
						
						while ( $objRow ) {
							/*$szReturn .= '<tr bgcolor="' . ( $bToggle ? "#f8f8f8" : "#f0f0f0" ) . '">';
							$szReturn .= ( '<td style="width: 60px;">' . $objRow->dnis . '</td>' );
							$szReturn .= ( '<td style="width: 120px;text-align: center;">' . $objRow->queuename . '</td>' );
							$szReturn .= ( '<td style="width: 100px;text-align: center;">' . $objRow->callerid . '</td>' );
							$szReturn .= ( '<td style="width: 100px;text-align: center;">' . $objRow->callername . '</td>' );
							$szReturn .= ( '<td style="width: 80px;text-align: center;">' . $objRow->callstatus . '</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->duration . '</td>' );
							$szReturn .= ( '<td style="width: 100px;text-align: center;">' . $objRow->agentname . '</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->agentid . '</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->agentext . '</td>' );
							$szReturn .= '</tr>';*/
							
							//$rows[] = array( 'id' => $nTotalRows, 'cell' => array( $objRow->dnis, $objRow->queuename, $objRow->callerid, 
							//													  $objRow->callername, 
							//				$objRow->callstatus, $objRow->duration, $objRow->agentname, $objRow->agentid,
							//				$objRow->agentext ));
							
							$rows[] =  array( $objRow->dnis, $objRow->queuename, $objRow->callerid, 
																				  $objRow->callername, 
											$objRow->callstatus, $objRow->duration, $objRow->agentname, $objRow->agentid,
											$objRow->agentext );
							
							$nTotalRows++;
							
							$objRow = $resultSet->fetch_object();
						}
						
					}
					
					
					$mySQL->close();
				}
				
				$result = array( 'aaData' => $rows);
				echo json_encode( $result );
				//$szReturn .= '</table>';
					
				//$szReturn = str_replace( ":", "<tcolon />", $szReturn);
					
				//echo 'adminstats:queues:1:' . $nConnected . ':' . $nQueue . ':' . $szReturn; 
			} else if ( $_GET['statstype'] == 'queuessummary' ) {
				$mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
				//$szReturn = '<table>';
				$szReturn = '';
				$nConnected = 0;
				$nQueue = 0;
				
				if ( $mySQL ) {
					$query = "SELECT tq.queuename AS queuename, callsrcvd, callsqueue, callsanssla, callsansnosla, abandon, abandonnotacc, callsxfer, callsorphaned, callsonhold, longestcall FROM rtqueueslabandon r INNER JOIN tenantqueues tq ON tq.queuenameinternal=r.queuename WHERE tq.tenantid=" . $_GET['tenantid'] . ";";
						
					$resultSet = $mySQL->query( $query );
					
					if ( $resultSet ) {
						$objRow = $resultSet->fetch_object();
						$bToggle = true;
						
						while ( $objRow ) {
							
							/*$szReturn .= '<tr bgcolor="' . ( $bToggle ? "#f8f8f8" : "#f0f0f0" ) . '">';
							$szReturn .= ( '<td style="width: 120px;">' . $objRow->queuename . '</td>' );
							$szReturn .= ( '<td style="width: 60px;text-align:center;">' . $objRow->callsrcvd . '</td>' );
							$szReturn .= ( '<td style="width: 60px;text-align:center;">' . $objRow->callsqueue . '</td>' );
							$szReturn .= ( '<td style="width: 60px;text-align:center;">' . $objRow->callsanssla . '</td>' );
							$szReturn .= ( '<td style="width: 60px;text-align:center;">' . $objRow->callsansnosla . '</td>' );
							$szReturn .= ( '<td style="width: 60px;text-align:center;">' . $objRow->abandon . '</td>' );
							$szReturn .= ( '<td style="width: 60px;text-align:center;">' . $objRow->abandonnotacc . '</td>' );
							$szReturn .= ( '<td style="width: 60px;text-align:center;">' . $objRow->callsxfer . '</td>' );
							$szReturn .= ( '<td style="width: 60px;text-align:center;">' . $objRow->callsorphaned . '</td>' );
							$szReturn .= ( '<td style="width: 60px;text-align:center;">' . round(($objRow->callsanssla * 100) / $objRow->callsrcvd, 2) . '%</td>' );
							$szReturn .= ( '<td style="width: 60px;text-align:center;">' . round(($objRow->callsansnosla * 100) / $objRow->callsrcvd, 2)  . '%</td>' );
							$szReturn .= ( '<td style="width: 60px;text-align:center;">' . round(($objRow->abandon * 100) / $objRow->callsrcvd, 2)  . '%</td>' );
							$szReturn .= ( '<td style="width: 60px;text-align:center;">' . round(($objRow->abandonnotacc * 100) / $objRow->callsrcvd, 2) . '%</td>' );
							$szReturn .= ( '<td style="width: 70px;text-align:center;">' . $objRow->longestcall . '</td>' );
							$szReturn .= '</tr>'; */
					
					        $szRow = $objRow->queuename . ':' . $objRow->callsrcvd . ':' . $objRow->callsqueue . ':' . $objRow->callsanssla .':' .
								$objRow->callsansnosla . ':' . $objRow->abandon . ':' .  $objRow->abandonnotacc . ':' . $objRow->callsorphaned . ':' .
								 round(($objRow->callsanssla * 100) / $objRow->callsrcvd, 2) . ':' . 
								 round(($objRow->callsansnosla * 100) / $objRow->callsrcvd, 2) . ':' .
								 round(($objRow->abandonnotacc * 100) / $objRow->callsrcvd, 2) . ':' .
								 round(($objRow->abandon * 100) / $objRow->callsrcvd, 2) . ':' .
								 round(($objRow->callsorphaned * 100) / $objRow->callsrcvd, 2) . ':' .
								 str_replace( ":", "<dcolon />" , $objRow->longestcall);
								 
							$szReturn .= $szRow . '|';
							
							$objRow = $resultSet->fetch_object();
							//$bToggle = ( $bToggle ? false : true );
						}
						
					}
					
					
					$mySQL->close();
				}
				
				//$szReturn .= '</table>';
					
				$szReturn = str_replace( ":", "<tcolon />", $szReturn);
					
				echo 'adminstats:queuessummary:1:' . $szReturn;
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