<?php

require_once('config.php'); 
require_once('errhandler.php');

class AgentUtil {
	private $mySQL;
	private $m_szErrorMessage = '';
	
	function __construct() {
		$this->mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
	}
	
	function __destruct() {
		$this->mySQL->close();
	}
	public function getUserStatusList($nTenantID) {
		$szReturn = '';
		if ( $this->mySQL ) {
			// $query = "SELECT * FROM usersstatus WHERE tenantid=0 OR tenantid=" . $nTenantID . " ORDER BY tenantid, userstatus ASC";
			$query = "(SELECT * FROM usersstatus us1 WHERE us1.tenantid=0 AND us1.userstatusid>0 ORDER BY us2.userstatusid ASC) UNION "."(SELECT * FROM usersstatus us2 WHERE  us2.usersstatusdbstatus = 'A' AND  us2.tenantid=" . $nTenantID . " ORDER BY us2.userstatus ASC)";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			$szReturn = '<select id="seluserstatus" width="180" style="width:180px" onchange="onUserStatusChange();">';
			while ( $objRow ) {
				$szReturn .= '<option value="' . $objRow->userstatusid . '">' . $objRow->userstatus . '</option>';
				$objRow = $resultSet->fetch_object();
			}
			$szReturn .= '</select>';
		}
		
		return $szReturn;
	}
	
	public function saveCallWrapups($szCallID, $szWrapups) {
		if ( $this->mySQL ) {
			$arrWrapups = explode( ",", $szWrapups );
			
			foreach ( $arrWrapups as $szWrapup ) {
				$query = "INSERT INTO callwrapups ( callid, wrapupid ) VALUES ( " . $szCallID . ", "  .
						$szWrapup . ");";
				
				$resultSet = $this->mySQL->query( $query );
			}
		}
	}
	
	public function getAgentOnlineSelection($nTenantID, $szExceptAgent) {
		$szReturn='<select id="selonlineagents" style="width:130px;" onchange="onselonlineagentsChange();">';
		
		if ( $this->mySQL ) {
			$query = "SELECT username FROM users WHERE userstatusid>0 AND username<>'" .
				$szExceptAgent . "' ORDER BY username ASC;";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			while ( $objRow ) {
				$szReturn .= '<option id="opt' . $objRow->username . '" value="' . $objRow->username . '">' . $objRow->username . '</option>';
				$objRow = $resultSet->fetch_object();
			}
		}
		
		$szReturn .= '</select>';
		
		return $szReturn;
	}
	
	public function getAgentStatus($szUsername) {
		$szReturn = "";
		
		if ( $this->mySQL ) {
			$query = "SELECT (SELECT userstatus FROM usersstatus us WHERE  us.userstatusid=u.userstatusid) AS userstatus FROM users u WHERE username='" . $szUsername ."'";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$szReturn = $objRow->userstatus;
			}
		}
		
		return $szReturn;
	}
        
        public function getUserStatusID($szUsername) {
		$szReturn = "";
		
		if ( $this->mySQL ) {
			$query = "SELECT userstatusid FROM users WHERE username='" . $szUsername ."'";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$szReturn = $objRow->userstatusid;
			}
		}
		
		return $szReturn;
	}
	
	public function getInboundWrapupList($nQueueID) {
		$szReturn = '';
		
		if ( $this->mySQL ) {
			$query = "SELECT w.wrapupid AS wrapupid, w.wrapup AS wrapup FROM queuewrapups qw " .
				"INNER JOIN tenantqueues tq ON tq.tenantqueueid=qw.tenantqueueid " .
				"INNER JOIN wrapups w ON w.wrapupid = qw.wrapupid WHERE " .
    			"tq.tenantqueueid=" . $nQueueID . " ORDER BY w.wrapup ASC";
				
			$resultSet = $this->mySQL->query( $query );
			
			$objRow = $resultSet->fetch_object();
			
			/*$szReturn = '<select id="selwrapup" width="180" style="width:180px">';
			while ( $objRow ) {
				$szReturn .= '<option value="' . $objRow->wrapupid . '">' . $objRow->wrapup . '</option>';
				$objRow = $resultSet->fetch_object();
			}
			$szReturn .= '</select>';*/
			
			$szReturn = '<table width="95%" style="background-color:#e6f0f1;border:1px solid black">';
			$nCnt = 1;
			while ( $objRow ) {
				$szReturn .= '<tr><td width="100%"><input type="checkbox" id="chkwrapup' . $nCnt . '" value="' . $objRow->wrapupid . 
					'">' . $objRow->wrapup . '</input><td></tr>';
				$nCnt++;
				$objRow = $resultSet->fetch_object();
			}
			$szReturn .= '</table>';
			
		}
		
		return $szReturn;
	}
	
	
}
?>
