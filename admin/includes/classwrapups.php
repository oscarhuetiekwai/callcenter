<?php

require_once('config.php'); 
require_once('errhandler.php');
require_once('classwrapup.php');

class Wrapups {
	private $mySQL;
	private $m_szErrorMessage = '';
	
	function __construct() {
		$this->mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
	}
	
	function __destruct() {
		$this->mySQL->close();
	}
	
	public function getWrapupCount($nTenantID) {
		$nWrapupCount = 0;
		
		if ( $this->mySQL ) {
			$query = "SELECT count(*) AS totalcount FROM wrapups WHERE tenantid=" . $nTenantID;
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$nWrapupCount = $objRow->totalcount;
			}
		}
		
		return $nWrapupCount;
	}
	
	public function getWrapups($nTenantID) {
		$arrWrapups = array();
		
		if ( $this->mySQL ) {
			$query = "SELECT * FROM wrapups WHERE wrapupdbstatus='A' ORDER BY wrapup ASC;";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			while ( $objRow ) {
				$wrapup = new Wrapup($objRow->wrapupid,$objRow->tenantid,$objRow->wrapup,$objRow->description);
				
				array_push( $arrWrapups, $wrapup );
				$objRow = $resultSet->fetch_object();
			}
		}
		
		return $arrWrapups;
	}
	
	public function getWrapupInfo($wrapupID) {
		$wrapupInfo = NULL;
		
		if ( $this->mySQL ) {
			$query = "SELECT * FROM wrapups WHERE wrapupid=" . $wrapupID;
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$wrapupInfo = new Wrapup($objRow->wrapupid,$objRow->tenantid,$objRow->wrapup,$objRow->description);
			}
		}
		
		return $wrapupInfo;
	}
	
	public function saveWrapup($wrapup) {
		$bReturn = false;
		
		if ( $wrapup ) {
			if ( $wrapup->getWrapup() == '' ) {
				$this->m_szErrorMessage = 'Please enter wrapup!!!';
				return $bReturn;
			}
		} else {
			$this->m_szErrorMessage = 'Uninitialize wrapup class!!!';
			return $bReturn;
		}
		
		if ( !$this->mySQL ) {
			$this->m_szErrorMessage = 'Error connecting to the database!!!';
			return $bReturn;
		}
		
		if ( $wrapup->getWrapupID() > 0 ) {
			// this is for update
			if ( $this->getWrapupname( $wrapup->getWrapupID() ) != $wrapup->getWrapup() ) {
				if ( $this->isWrapupExist( $wrapup->getWrapup(), $wrapup->getTenantID() ) ) {
					$this->m_szErrorMessage = 'Wrapup "' . $wrapup->getWrapup() . '" is already in the database. Please change the wrapup name!!!';
					return $bReturn;
				}
			}
			
			$query = "UPDATE wrapups SET tenantid=" . $wrapup->getTenantID() . ", wrapup='" . $wrapup->getWrapup() . 
				"', description='" . $wrapup->getDescription() . "' WHERE wrapupid=" .	$wrapup->getWrapupID();
			
			$resultSet = $this->mySQL->query($query);
			
			$bReturn = true;
		} else {
			// this is for adding new items
			if ( $this->isWrapupExist( $wrapup->getWrapup(), $wrapup->getTenantID() ) ) {
				$this->m_szErrorMessage = 'Wrapup "' . $wrapup->getWrapup() . '" is already in the database. Please change the wrapup name!!!';
				return $bReturn;
			}
			
			$query = "INSERT INTO wrapups (tenantid, wrapup, description) VALUES (" .
				$wrapup->getTenantID() . ", '" . $wrapup->getWrapup() . "', '" . $wrapup->getDescription() . "');";
			
			$resultSet = $this->mySQL->query($query);
			
			$bReturn = true;
		}
		
		return $bReturn;
	}
	
	public function displayWrapupList($nTenantID) {
		$szReturn = '';
		$nCnt = 1;
		
		foreach ( $this->getWrapups($nTenantID) as $wrapup) {
			$szReturn = $szReturn . '<label for="chkuser' . 
						$nCnt . '" onclick="onWrapupClick(' . $wrapup->getWrapupID() . ');"><input type="checkbox" id="chkwrapup' . $nCnt . 
						'" name="chkwrapup" value="' . $wrapup->getWrapupID() . '" /><b><font color="#009999">' . $wrapup->getWrapup().
						'</font></b></label>';
			$nCnt++;
		}
		
		return $szReturn;
	}
	
	private function getWrapupname($wrapupID) {
		$szReturn = '';
		
		if ( $this->mySQL ) {
			$query = "SELECT * FROM wrapups WHERE wrapupid=" . $wrapupID;
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$szReturn = $objRow->wrapup;
			}
		}	
		
		return $szReturn;
	}
	
	private function isWrapupExist( $szWrapup, $ntenantID ) {
		$bReturn = false;
		
		if ( $this->mySQL ) {
			$query = "SELECT * FROM wrapups WHERE wrapupdbstatus='A' AND tenantid=" . $ntenantID . " AND wrapup='" . $szWrapup . "'";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$bReturn = true;
			}
		}
		
		return $bReturn;
	}

        public function deleteWrapup($wrapupID) {
                if ( $this->mySQL ) {
                        $query = "UPDATE wrapups SET wrapupdbstatus='D' WHERE wrapupid=" . $wrapupID . ";";
                        $resultSet = $this->mySQL->query( $query );

                        return $resultSet;
                }
        }
	
	public function getLastErrorMessage() { return $this->m_szErrorMessage; }
}

?>