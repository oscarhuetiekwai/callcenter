<?php

require_once('config.php'); 
require_once('errhandler.php');
require_once('classunavailcode.php');

class UnavailCodes {
	private $mySQL;
	private $m_szErrorMessage = '';
	
	function __construct() {
		$this->mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
	}
	
	function __destruct() {
		$this->mySQL->close();
	}
	
	public function getUnavailablesCount($tenantID) {
		$nUnavailsCount = 0;
		
		if ( $this->mySQL ) {
			$query = "SELECT count(*) AS totalcount FROM usersstatus WHERE tenantid=0 OR tenantid=" . $tenantID;
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$nUnavailsCount = $objRow->totalcount;
			}
		}
		
		return $nUnavailsCount;
	}
	
	public function getUnavailInfo($unavailID) {
		$unavail = NULL;
		
		if ( $this->mySQL ) {
			$query = "SELECT * FROM usersstatus where userstatusid=" . $unavailID;
		
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$unavail = new UnavailCode($objRow->userstatusid,$objRow->tenantid,$objRow->userstatus,$objRow->productive);				
			}
		}
		
		return $unavail;
	}
	
	public function saveUnavail($unavail) {
		$bReturn = false;
		
		if ( $unavail ) {
			if ( $unavail->getUserStatus() == '' ) {
				$this->m_szErrorMessage = 'Please enter unavail name!!!';
				return $bReturn;
			} 
		} else {
			$this->m_szErrorMessage = 'Uninitialize Unavailable code class!!!';
			return $bReturn;
		}
		
		if ( !$this->mySQL ) {
			$this->m_szErrorMessage = 'Error connecting to the database!!!';
			return $bReturn;
		}
		
		if ( $unavail->getUserStatusID() > 0 ) {
			// this is for update
			if ( $this->getUserStatus( $unavail->getUserStatusID() ) != $unavail->getUserStatus() ) {
				if ( $this->isUserStatusExist( $unavail->getTenantID(), $unavail->getUserStatus() ) ) {
					$this->m_szErrorMessage = 'Unavailable Code "' . $unavail->getUserStatus() . '" is already in the database. Please change the name!!!';
					return $bReturn;
				}
			}
				
			$query = "UPDATE usersstatus SET tenantid=" . $unavail->getTenantID() . ", userstatus='" .
				$unavail->getUserStatus() . "', productive=" . $unavail->getProductive() . 
				" WHERE userstatusid=" . $unavail->getUserStatusID();
			
			$resultSet = $this->mySQL->query($query);
			
			$bReturn = true;
			

		} else {
			// this is for adding new items
			if ( $this->isUserStatusExist( $unavail->getTenantID(), $unavail->getUserStatus() ) ) {
				$this->m_szErrorMessage = 'Unavailable Code "' . $unavail->getUserStatus() . '" is already in the database. Please change the name!!!';
				return $bReturn;
			}
			
			$query = "INSERT INTO usersstatus (tenantid, userstatus, productive) VALUES (" . $unavail->getTenantID() . ", '" .
				$unavail->getUserStatus() . "', " . $unavail->getProductive() . ");";
			
			$resultSet = $this->mySQL->query($query);
			
			$bReturn = true;
			
		}
		
		return $bReturn;
	}
	
	public function getUnavailableCodes($tenantID, $filter) {
		$arrayUnavailCodes = array();
		
		if ( $this->mySQL ) {
			$filterCondition = "";
			
			if ( $filter == 2 ) {
				// productive
				$filterCondition = " AND productive=1 ";
			} else if ( $filter == 3 ) {
				// unproductive
				$filterCondition = " AND productive=0 ";
			}
			
			$query = "SELECT * FROM usersstatus WHERE usersstatusdbstatus='A' AND userstatusid > 0  AND (tenantid=0 OR tenantid=" . $tenantID . ") " .
				$filterCondition . " ORDER BY tenantid, userstatus ASC;";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			while ( $objRow ) {
				$unavail = new UnavailCode( $objRow->userstatusid, $objRow->tenantid, $objRow->userstatus, $objRow->productive );
				
				if ( $unavail ) array_push( $arrayUnavailCodes, $unavail );
				
				$objRow = $resultSet->fetch_object();
			}
		}
		
		return $arrayUnavailCodes;
	}
	
	public function displayUnavailableCodes($tenantID, $filter) {
		$szReturn = '';
		$nCnt = 1;
		
		foreach ( $this->getUnavailableCodes($tenantID, $filter) as $unavail) {
			$szReturn = $szReturn . '<label for="chkunavail' . 
						$nCnt . '" onclick="onUnavailCodeClick(' . $unavail->getUserStatusID() . ');"' . 
						( $unavail->getUserStatusID() < 100 ? '  style="background-color:lightblue;"' : '' ) .'><input type="checkbox" id="chkunavail"' . $nCnt . 
						'" name="chkunavail" value="' . $unavail->getUserStatusID() . '" /><b><font color="#009999">' . $unavail->getUserStatus() . 
						'</font></b></label>';
			$nCnt++;
		}
		
		return $szReturn;
	}

        public function deleteUnavail($unavailID) {
                if ( $this->mySQL ) {
                        $query = "UPDATE usersstatus SET usersstatusdbstatus='D' WHERE userstatusid=" . $unavailID . ";";
                        $resultSet = $this->mySQL->query( $query );

                        return $resultSet;
                }
        }
	
	public function getLastErrorMessage() { return $this->m_szErrorMessage; }
	
	private function getUserStatus($userstatusID) {
		$szUserStatus = '';
		
		if ( $this->mySQL ) {
			$query = "SELECT userstatus FROM usersstatus WHERE userstatusid=" . $userstatusID;
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$szUserStatus = $objRow->userstatus;
			}
		}
		
		return $szUserStatus;
	}
	
	private function isUserStatusExist($tenantID,$userstatus) {
		$bReturn = false;
		
		if ( $this->mySQL ) {
			$query = "SELECT * FROM usersstatus WHERE usersstatusdbstatus='A' AND tenantid=" . $tenantID . " AND userstatus='" . $userstatus . "';";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$bReturn = true;
			}
		}
		
		return $bReturn;
	}
	
}

?>