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
			if ( $_GET['statstype'] == 'agents' ) {
				$mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
				$szReturn = '<table>';
				$nAvailable = 0;
				$nRinging = 0;
				$nBusy = 0;
				$nAfterCallWork = 0;
				$nOthers = 0;
				$nHold = 0;
				
				if ( $mySQL ) {
					$query = "SELECT u.userid AS agentid, u.username AS agentname, u.userexten AS agentext, uss.userstatus AS agentstatus, " . 
						"TIME(u.lastlogin) AS lastlogin, us.queue AS queue, SEC_TO_TIME(TIMESTAMPDIFF(SECOND, u.lastlogin, CURRENT_TIMESTAMP) ) AS ".
						"loginduration, SEC_TO_TIME(TIMESTAMPDIFF(SECOND, u.statustimestamp, CURRENT_TIMESTAMP) ) AS statusduration, " .
                        "uss1.userstatus AS lastagentstatus, us.callerid AS callerid, u.userstatusid AS curuserstatusid FROM userstatus us " . 
						"INNER JOIN users u ON u.userid=us.userid " .
						"INNER JOIN usersstatus uss ON uss.userstatusid=u.userstatusid " .
						"INNER JOIN usersstatus uss1 ON uss1.userstatusid=u.lastuserstatusid " .
						"WHERE u.tenantid=" . $_GET['tenantid'] . " AND u.userstatusid>0  AND u.userlevel=2 ORDER BY u.username ASC;";
						
					$resultSet = $mySQL->query( $query );
					
					if ( $resultSet ) {
						$objRow = $resultSet->fetch_object();
						$bToggle = true;
						
						while ( $objRow ) {
							$szReturn .= '<tr bgcolor="' . ( $bToggle ? "#f8f8f8" : "#f0f0f0" ) . '">';
							$szReturn .= ( '<td style="width: 50px;">' . $objRow->agentid . '</td>' );
							$szReturn .= ( '<td style="width: 120px;text-align: center;">' . $objRow->agentname . '</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->agentext . '</td>' );
							$szReturn .= ( '<td style="width: 60px;text-align: center;">' . $objRow->agentstatus . '</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->statusduration . '</td>' );
							$szReturn .= ( '<td style="width: 120px;text-align: center;">' . $objRow->queue . '</td>' );
							$szReturn .= ( '<td style="width: 100px;text-align: center;">' . $objRow->callerid . '</td>' );
							$szReturn .= ( '<td style="width: 100px;text-align: center;">N/A</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->lastlogin . '</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->loginduration . '</td>' );
							$szReturn .= ( '<td style="width: 60px;text-align: center;">' . $objRow->lastagentstatus . '</td>' );
							$szReturn .= '</tr>';
							
							switch ( $objRow->curuserstatusid ) {
								case 1: /* Available */
									$nAvailable++;
									break;
								case 2: /* Ringing */
									$nRinging++;
									break;
								case 3: /* Busy */
									$nBusy++;
									break;
								//case 4: /* Hold */
								//	$nHold++;
								//	break;
								case 5: /* After Call Work */
									$nAfterCallWork++;
									break;
								default:
									$nOthers++;
									break;
							}

							$objRow = $resultSet->fetch_object();
							$bToggle = ( $bToggle ? false : true );
						}
						
					}
					
					
					$mySQL->close();
				}
				
				$szReturn .= '</table>';
					
				$szReturn = str_replace( ":", "<tcolon />", $szReturn);
					
				echo 'adminagentstats:agents:1:' . $nAvailable . ':' . $nRinging . ':' . $nBusy . ':' . $nHold . ':' .  $nAfterCallWork . ':' .
					$nOthers . ':' . $szReturn; 
			} else if ( $_GET['statstype'] == 'agentsummary' ) {
				$mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
				$szReturn = '<table>';
				
				if ( $mySQL ) {
					$query = "SELECT u.userid AS agentid, u.username AS agentname, u.userexten AS agentext, ras.totalcallsrcvd AS totalcallsrcvd, " .
						"ras.totalcallsans AS totalcallsans, ras.totalcallsmissed AS totalcallsmissed, ras.totalcallsabandon AS " .
						"totalcallsabandon, ras.totalcallout AS totalcallout, SEC_TO_TIME(ras.totaltalktime) AS " .
						"totaltalktime, SEC_TO_TIME(ras.totalholdtime) AS totalholdtime, SEC_TO_TIME(ras.totalacwtime) AS totalacwtime, " .
						"SEC_TO_TIME( ras.totalanstime / ras.totalcallsans ) AS avganstime, SEC_TO_TIME(ras.totaltalktime/ras.totalcallsans) " .
						"AS avgtalktime, SEC_TO_TIME( ras.totalholdtime / ras.totalcallsans ) AS avgholdtime, " .
						"SEC_TO_TIME(ras.totalacwtime / ras.totalcallsans ) AS avgacwtime, " .
						"SEC_TO_TIME( ras.totaltalktime + ras.totalacwtime ) AS totalhandlingtime, " .
						"SEC_TO_TIME( ( ras.totaltalktime + ras.totalacwtime ) / ras.totalcallsans ) AS avghandlingtime " .
						"FROM rtagentsummary ras " .
						"INNER JOIN users u ON u.userid=ras.userid WHERE u.tenantid=" . $_GET['tenantid'] . " AND u.userlevel=2 ORDER BY u.username ASC;";
						
					$resultSet = $mySQL->query( $query );
					
					if ( $resultSet ) {
						$objRow = $resultSet->fetch_object();
						$bToggle = true;
						
						while ( $objRow ) {
							$szReturn .= '<tr bgcolor="' . ( $bToggle ? "#f8f8f8" : "#f0f0f0" ) . '">';
							$szReturn .= ( '<td style="width: 50px;">' . $objRow->agentid . '</td>' );
							$szReturn .= ( '<td style="width: 120px;text-align: center;">' . $objRow->agentname . '</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->agentext . '</td>' );
							$szReturn .= ( '<td style="width: 40px;text-align: center;">' . $objRow->totalcallsrcvd . '</td>' );
							$szReturn .= ( '<td style="width: 40px;text-align: center;">' . $objRow->totalcallsans . '</td>' );
							$szReturn .= ( '<td style="width: 40px;text-align: center;">' . $objRow->totalcallsmissed . '</td>' );
							$szReturn .= ( '<td style="width: 40px;text-align: center;">' . $objRow->totalcallsabandon . '</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->totalcallout . '</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->totaltalktime . '</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->totalholdtime . '</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->totalacwtime . '</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->totalhandlingtime . '</td>' );
							//$szReturn .= ( '<td style="width: 50px;text-align: center;"></td>' );
							//$szReturn .= ( '<td style="width: 50px;text-align: center;"></td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->avganstime . '</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->avgtalktime . '</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->avgholdtime . '</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->avgacwtime . '</td>' );
							$szReturn .= ( '<td style="width: 50px;text-align: center;">' . $objRow->avghandlingtime . '</td>' );
							//$szReturn .= ( '<td style="width: 50px;text-align: center;"></td>' );
							$szReturn .= '</tr>';


							$objRow = $resultSet->fetch_object();
							$bToggle = ( $bToggle ? false : true );
						}
						
					}
					
					
					$mySQL->close();
				}
				
				$szReturn .= '</table>';
					
				$szReturn = str_replace( ":", "<tcolon />", $szReturn);
					
				echo 'adminagentstats:agentsummary:1:' . $szReturn; 
			}
		}
	}
?>