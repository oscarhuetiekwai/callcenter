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
		$mysql =  new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);	
		
		if ( $mysql ) {
			$query = "select sf_web_gettenantrtcallcount(tenantqueueid) AS callcount, sf_web_gettenantrtabandoncount(tenantqueueid) " .
				" AS abandoncount, sf_web_gettenantrtwithinslcount(tenantqueueid) AS withinsl, queuename FROM tenantqueues WHERE " .
				"tenantid=" . $_GET['tenantid'];
			
			$resultSet = $mysql->query( $query );
			$objRow = $resultSet->fetch_object();
			
			$totalcall = 0;
			$totalabandon = 0;
			$totalsl = 0;
			$queuetotalcall = "";
			
			$return = '<table><tr><th>Virtual Queue</th><th>Total Calls</th><th>Total Abandon</th><th>Total WithIn SL</th>' .
				'<th>% Abandon</th><th>% Within SL</th></tr>';
			while ( $objRow ) {
				$totalcall = $totalcall + $objRow->callcount;
				$totalabandon = $totalabandon + $objRow->abandoncount;
				$totalsl = $totalsl + $objRow->withinsl;
				if ( $queuetotalcall == "" ) 
					$queuetotalcall = $objRow->queuename . ':' . $objRow->callcount;
				else
					$queuetotalcall .= ( '|' . $objRow->queuename . ':' . $objRow->callcount );
									   
				$return .= '<tr>';
				$return .= '<td>' . $objRow->queuename . '</td>';
				$return .= '<td align="center">' . ($objRow->callcount == '0'? '-':$objRow->callcount) . '</td>';
				$return .= '<td align="center">' . ($objRow->abandoncount == '0'? '-':$objRow->abandoncount)  . '</td>';
				$return .= '<td align="center">' . ($objRow->withinsl == '0'? '-':$objRow->withinsl) . '</td>';
				$return .= '<td align="center">' . ($objRow->abandoncount == '0'? '-':number_format((($objRow->abandoncount * 100 )/$objRow->callcount),2,'.',''))  . '&#37;</td>';
				$return .= '<td align="center">' . ($objRow->withinsl == '0'? '-':number_format((($objRow->withinsl * 100 )/($objRow->callcount - $objRow->abandoncount)), 2, '.', ''))  . '&#37;</td>';
				$return .= '</tr>';
				$objRow = $resultSet->fetch_object();
			}
			
			$return .= '<tr>';
			$return .= '<td>Total</td>';
			$return .= '<td align="center">' . $totalcall . '</td>';
			$return .= '<td align="center">' . ($totalabandon == '0'? '-':$totalabandon)  . '</td>';
			$return .= '<td align="center">' . ($totalsl == '0'? '-':$totalsl) . '</td>';
			$return .= '<td align="center">' . ($totalabandon == '0'? '-':number_format((($totalabandon * 100 )/$totalcall),2, '.', ''))  . '&#37;</td>';
			$return .= '<td align="center">' . ($totalsl == '0'? '-':number_format((($totalsl * 100 )/($totalcall - $totalabandon)),2, '.', ''))  . '&#37;</td>';
			$return .= '</tr>';
			
			$return .= '</table>';
			
			//$queuetotalcall = str_replace(":", "<dquote />", $queuetotalcall);
			//$queuetotalcall = str_replace("|", "<dpipe />", $queuetotalcall);
			
			echo 'vqueue:' . $totalcall . ':' . $totalabandon . ':' . $totalsl . ':' . $return;
			
			$_SESSION['WEB_ADMIN_VQUEUE_GRAPH'] = $queuetotalcall;
			$mysql->close();
		}
	}
?>