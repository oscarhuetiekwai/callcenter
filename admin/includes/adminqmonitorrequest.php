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
	
	if ( isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		if ( isset($_GET['adminqmonitor']) ) {
			$mySQL = new mysqli(DB_HOST_REPORT,DB_USER_REPORT,DB_PASS_REPORT,DB_DATABASE_REPORT);
			$szReturn = '<table>';
			$totalcallstatuscount = 0;
			$totalpages = 0;
			$curpage = 1;
			$itemperpage = 10;
			$agentID = 0;
			$extension='';
			
			if ( isset( $_GET['page'] ) ) {
				$curpage = $_GET['page'];
			}
			
			if ( isset( $_GET['agentid'] ) && $_GET['agentid'] != '' ) {
				if ( $_GET['agentid'] > 0 ) {
					$agentID = $_GET['agentid'];
				}
			}
			
			if ( isset( $_GET['ext']) && $_GET['ext'] != '' ) {
				$extension = $_GET['ext'];
			}
			
			$conditions = '';
				
			if ( $agentID > 0 ) {
				$conditions = ' WHERE cs.userid=' . $agentID;
			}
			
			if ( isset($_GET['date1']) && isset($_GET['date2']) && $_GET['date1'] != '' && $_GET['date2'] != '' ) {
				if ( $conditions == '' ) {
					$conditions = ' WHERE ( cs.queuetime>="' . $_GET['date1'] . '" AND cs.queuetime<="' . $_GET['date2'] . 
						'")';
				} else {
					$conditions .= ' AND ( cs.queuetime>="' . $_GET['date1'] . '" AND cs.queuetime<="' . $_GET['date2'] . 
						'")';
				}
			}
			
			if ( $extension != '' && $extension != '0' ) {
				if ( $conditions == '' ) {
					$conditions = ' WHERE cs.extension=\'' . $extension . '\'';
				} else {
					$conditions .= ' AND cs.extension=\'' . $extension . '\'';
				}
			}
			
			if ( isset($_GET['callerid']) && $_GET['callerid'] != '' ) {
				if ( $conditions == '' ) {
					$conditions = ' WHERE cs.callerid LIKE "%' . $_GET['callerid'] . '%"';
				} else {
					$conditions .= ' AND ( cs.callerid LIKE "%' . $_GET['callerid'] . '%")';
				}
			}

			if ( $mySQL ) {
				//$resultSet = $mySQL->query( 'SELECT COUNT(*) FROM callstatus cs ' .
				//	'INNER JOIN users u ON u.userid=cs.userid ' .
				//	'INNER JOIN cdr ON cdr.uniqueid=cs.callid ' .
				//	$conditions );
				
				$resultSet = $mySQL->query( 'SELECT COUNT(*) AS totalcalls FROM callcontactsdetails cs ' .
					$conditions );
							
				if ( $resultSet ) {
					$objRow = $resultSet->fetch_object();
					
					$totalcallstatuscount = $objRow->totalcalls;
				}
				
				// compute for number of pages
				$totalpages = ( ( $totalcallstatuscount - ( $totalcallstatuscount % $itemperpage ) ) / $itemperpage ) + ( ( $totalcallstatuscount % $itemperpage ) > 0 ? 1 : 0 );
				
				//$resultSet = $mySQL->query( "SELECT cs.callid AS callid, DATE(cs.timestamp) AS calldate, TIME(cs.timestamp) " .
				// "AS calltime, u.username AS agent, cdr.dst AS exten, cdr.src AS callerid, tq.queuename AS queuename, " .
				// "cs.callduration AS callduration FROM callstatus cs LEFT JOIN users u ON u.userid=cs.userid INNER JOIN " .
				// "cdr cdr ON cdr.uniqueid=cs.callid LEFT JOIN tenantqueues tq ON tq.queuenameinternal=cs.queue ORDER BY " .
				// "cs.timestamp DESC LIMIT " .  $curpage - 1 . ", " . $curpage * $itemperpage . ";");

 				//$resultSet = $mySQL->query( 'SELECT cs.callid AS callid, DATE(cs.timestamp) AS calldate, TIME(cs.timestamp) ' .
				// 'AS calltime, u.username AS agent, cdr.dst AS exten, cdr.src AS callerid, tq.queuename AS queuename, ' .
				// 'cs.callduration AS callduration FROM callstatus cs LEFT JOIN users u ON u.userid=cs.userid INNER JOIN ' .
				// 'cdr cdr ON cdr.uniqueid=cs.callid LEFT JOIN tenantqueues tq ON tq.queuenameinternal=cs.queue ' .
				// 'WHERE cs.userid=' . $agentID . ' ' .
				// ' ORDER BY cs.timestamp DESC LIMIT ' .  ($curpage - 1) . ', ' . ( $curpage * $itemperpage ) );
				
				
				
				$resultSet = $mySQL->query( 'SELECT cs.callid AS callid, DATE(cs.ivrtime) AS calldate, TIME(cs.queuetime) AS calltime, ' .
					'u.username AS agent, cs.extension AS exten, cs.callerid AS callerid, cs.queuename AS queuename, ' .
					'cs.callduration AS callduration ' .
					'FROM callcontactsdetails cs ' .
					'LEFT JOIN users u ON u.userid=cs.userid ' .
					$conditions .
				 	' ORDER BY cs.ivrtime DESC LIMIT ' .  (($curpage - 1) * $itemperpage) . ', ' . ( $curpage * $itemperpage ) );
				 
				//echo 'SELECT cs.callid AS callid, DATE(cs.ivrtime) AS calldate, TIME(cs.ivrtime) AS calltime, ' .
                                //        'u.username AS agent, cs.extension AS exten, cs.callerid AS callerid, cs.queuename AS queuename, ' .
                               //         'cs.callduration AS callduration ' .
                               //         'FROM callcontactsdetails cs ' .
                               //         'LEFT JOIN users u ON u.userid=cs.userid ' .
                               //         $conditions .
                               //         ' ORDER BY cs.ivrtime DESC LIMIT ' .  ($curpage - 1) . ', ' . ( $curpage * $itemperpage );
				if ( $resultSet ) {
					$objRow = $resultSet->fetch_object();
					$bToggle = true;
					$filepath='/var/spool/asterisk/monitor/';
                                        $archivedpath='/mnt/nas/';
                                        $recordingpath='';
					
					while ( $objRow ) {
						$recordingfile = '';
						
						if ( is_file( $filepath . $objRow->queuename . '-' . $objRow->callid . '.mp3' ) ) {
							$recordingfile = $objRow->queuename . '-' . $objRow->callid . '.mp3';
                                                        $recordingpath = $filepath;
						} else if ( is_file( $filepath . $objRow->queuename . '-' . $objRow->callid . '.wav' ) ) {
							$recordingfile = $objRow->queuename . '-' . $objRow->callid . '.wav';
                                                        $recordingpath = $filepath;
						} else if ( is_file( $filepath . $objRow->queuename . '-' . $objRow->callid . '.WAV' ) ) {
							$recordingfile = $objRow->queuename . '-' . $objRow->callid . '.WAV';
                                                        $recordingpath = $filepath;
						} else if ( is_file( $filepath . $objRow->callid . '.WAV' ) ) {
							$recordingfile = $objRow->callid . '.WAV';
                                                        $recordingpath = $filepath;
						} else if ( is_file( $filepath . $objRow->callid . '.wav' ) ) {
							$recordingfile = $objRow->callid . '.wav';
                                                        $recordingpath = $filepath;
						}  else if ( is_file( $filepath . $objRow->callid . '.mp3' ) ) {
							$recordingfile = $objRow->callid . '.mp3';
                                                        $recordingpath = $filepath;
						} else if ( is_file( $archivedpath . $objRow->queuename . '-' . $objRow->callid . '.mp3' ) ) {
							$recordingfile = $objRow->queuename . '-' . $objRow->callid . '.mp3';
                                                        $recordingpath = $archivedpath;
						} else if ( is_file( $archivedpath . $objRow->queuename . '-' . $objRow->callid . '.wav' ) ) {
							$recordingfile = $objRow->queuename . '-' . $objRow->callid . '.wav';
                                                        $recordingpath = $archivedpath;
						} else if ( is_file( $archivedpath . $objRow->queuename . '-' . $objRow->callid . '.WAV' ) ) {
							$recordingfile = $objRow->queuename . '-' . $objRow->callid . '.WAV';
                                                        $recordingpath = $archivedpath;
						} else if ( is_file( $archivedpath . $objRow->callid . '.WAV' ) ) {
							$recordingfile = $objRow->callid . '.WAV';
                                                        $recordingpath = $archivedpath;
						} else if ( is_file( $archivedpath . $objRow->callid . '.wav' ) ) {
							$recordingfile = $objRow->callid . '.wav';
                                                        $recordingpath = $archivedpath;
						} else if ( is_file( $archivedpath . $objRow->callid . '.mp3' ) ) {
							$recordingfile = $objRow->callid . '.mp3';
                                                        $recordingpath = $archivedpath;
						}
						
						$szReturn .= '<tr bgcolor="' . ( $bToggle ? "#f8f8f8" : "#f0f0f0" ) . '">';
						$szReturn .= '<td style="width:25px;height:26px;"><input type="checkbox" value="' . $objRow->callid . '"/></td>';
						$szReturn .= '<td style="width:80px;text-align: center;">' . $objRow->calldate . '</td>';
						$szReturn .= '<td style="width:60px;text-align: center;">' . $objRow->calltime . '</td>';
						$szReturn .= '<td style="width:120px;text-align: center;">' . $objRow->agent . '</td>';
						$szReturn .= '<td style="width:80px;text-align: center;">' . $objRow->exten . '</td>';
						$szReturn .= '<td style="width:100px;text-align: center;">' . $objRow->callerid . '</td>';
						$szReturn .= '<td style="width:120px;text-align: center;">' . $objRow->queuename . '</td>';
						$szReturn .= '<td style="width:40px;text-align: center;">' . $objRow->callduration . '</td>';
						/* $szReturn .= '<td style="width:80px;text-align: center;"></td>'; */
						if ( $recordingfile == '' ) {
							$szReturn .= '<td style="width:40px;text-align: center;"></td>';
						} else {
							$szReturn .= '<td style="width:40px;text-align: center;"><a href="#" onclick="playRecord(\'' . $recordingfile . 
									 '\',\'' . $recordingpath . '\' );"><img src="images/btnplay.png" /></a></td>';
						}
						$szReturn .= '</tr>';
						$objRow = $resultSet->fetch_object();
						$bToggle = ( $bToggle ? false : true );
					}
				}
				
				
				$mySQL->close();
			}
			
			$szReturn .= '</table>';
			
			$szReturn = str_replace( ":", "<tcolon />", $szReturn);
			
			echo 'adminqmonitor:search:1:' . $totalcallstatuscount . ':' . $totalpages . ':' . $curpage . ':' . $szReturn;
		}
	}
?>
