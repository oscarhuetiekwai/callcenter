<?php

require_once('config.php'); 
require_once('errhandler.php');
require_once('classoutbound.php');

class Outbounds {
	private $mySQL;
	private $m_szErrorMessage = '';
	
	function __construct() {
		$this->mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
	}
	
	function __destruct() {
		$this->mySQL->close();
	}
	
	public function getLastErrorMessage() { return $this->m_szErrorMessage; }
	
	public function getOutboundCount($nTenantID) {
		$nOutboundCount = 0;
		
		if ( $this->mySQL ) {
			$query = "SELECT count(*) AS totalcount FROM outbounds WHERE tenantid=" . $nTenantID;
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$nOutboundCount = $objRow->totalcount;
			}
		}
		
		return $nOutboundCount;
	}
	
	public function getOutbounds($nTenantID) {
		$arrOutbounds = array();
		
		if ( $this->mySQL ) {
			$query = "SELECT * FROM outbounds ORDER BY outboundname ASC;";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			while ( $objRow ) {
				$outbound = new Outbound($objRow->outboundid,$objRow->tenantid,$objRow->outboundname,$objRow->outboundtype,
										 $objRow->outboundstart,$objRow->outboundend);
				
				array_push( $arrOutbounds, $outbound );
				$objRow = $resultSet->fetch_object();
			}
			
			// get the outboundsched
			foreach( $arrOutbounds as $outbound ) {
				$query = "SELECT * FROM outboundsschedules WHERE outboundid=" . $outbound->getOutboundID();
				
				$resultSet = $this->mySQL->query($query);
			
				$objRow = $resultSet->fetch_object();
			
				while ( $objRow ) {
					$outboundsched = new OutboundSched($objRow->outboundday,$objRow->outbounddayenable,
													   $objRow->timestart, $objRow->timeend);
					$outbound->addOutboundSched($outboundsched);
					$objRow = $resultSet->fetch_object();
				}
			}
		}
		
		return $arrOutbounds;
	}
	
	public function getOutboundInfo($outboundID) {
		$outboundInfo = NULL;
		
		if ( $this->mySQL ) {
			$query = "SELECT * FROM outbounds WHERE outboundid=" . $outboundID;
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$outboundInfo = new Outbound($objRow->outboundid,$objRow->tenantid,$objRow->outboundname,$objRow->outboundtype,
										 $objRow->outboundstart,$objRow->outboundend);
			}
			
			if ( $outboundInfo != NULL ) {
				$query = "SELECT * FROM outboundsschedules WHERE outboundid=" . $outboundInfo->getOutboundID();
				
				$resultSet = $this->mySQL->query($query);
			
				$objRow = $resultSet->fetch_object();
			
				while ( $objRow ) {
					$outboundsched = new OutboundSched($objRow->outboundday,$objRow->outbounddayenable,
													   $objRow->timestart, $objRow->timeend);
					$outboundInfo->addOutboundSched($outboundsched);
					$objRow = $resultSet->fetch_object();
				}
				
				$query = "SELECT skillid FROM outboundsskills WHERE outboundid=" .  $outboundInfo->getOutboundID();
				
				$resultSet = $this->mySQL->query($query);
			
				$objRow = $resultSet->fetch_object();
				
				while ( $objRow ) {
					$outboundInfo->addOutboundSkill( $objRow->skillid );
					$objRow = $resultSet->fetch_object();
				}
				
				$query = "SELECT wrapupid FROM outboundswrapups WHERE outboundid=" .  $outboundInfo->getOutboundID();
				
				$resultSet = $this->mySQL->query($query);
			
				$objRow = $resultSet->fetch_object();
				
				while ( $objRow ) {
					$outboundInfo->addOutboundWrapup( $objRow->wrapupid );
					$objRow = $resultSet->fetch_object();
				}
				
				
			}
		}
		
		return $outboundInfo;
	}
	
	
	public function saveOutbound($outbound) {
		$bReturn = false;
		
		if ( $outbound ) {
			if ( $outbound->getOutbound() == '' ) {
				$this->m_szErrorMessage = 'Please enter outbound name!!!';
				return $bReturn;
			}
		} else {
			$this->m_szErrorMessage = 'Uninitialize outbound class!!!';
			return $bReturn;
		}
		
		if ( !$this->mySQL ) {
			$this->m_szErrorMessage = 'Error connecting to the database!!!';
			return $bReturn;
		}
		
		if ( $outbound->getOutboundID() > 0 ) {
			// this is for update
			if ( $this->getOutboundName( $outbound->getOutboundID() ) != $outbound->getOutbound() ) {
				if ( $this->isOutboundExist( $outbound->getOutbound(), $outbound->getTenantID() ) ) {
					$this->m_szErrorMessage = 'Outbound "' . $outbound->getOutbound() . '" is already in the database. Please change the outbound name!!!';
					
					return $bReturn;
				}
			}
			
			$query = "UPDATE outbounds SET tenantid=" . $outbound->getTenantID() . ", outboundname='" . $outbound->getOutbound() . 
				"', outboundtype=" . $outbound->getOutboundType() . ", outboundstart='" . $outbound->getOutboundStart() .
				"', outboundend='" . $outbound->getOutboundEnd() . "' WHERE outboundid=" .	$outbound->getOutboundID();
			
			$resultSet = $this->mySQL->query($query);
			
			$bReturn = true;
		} else {
			// this is for adding new items
			if ( $this->isOutboundExist( $outbound->getOutbound(), $outbound->getTenantID() ) ) {
				$this->m_szErrorMessage = 'Outbound "' . $outbound->getOutbound() . '" is already in the database. Please change the outbound name!!!';
				return $bReturn;
			}
			
			$query = "INSERT INTO outbounds (tenantid, outboundname, outboundtype, outboundstart, outboundend) VALUES (" .
				$outbound->getTenantID() . ", '" . $outbound->getOutbound() . "', " . $outbound->getOutboundType() . ", '" .
				$outbound->getOutboundStart() . "', '" . $outbound->getOutboundEnd() . "')";
			
			$resultSet = $this->mySQL->query($query);
			
			$query = "SELECT outboundid FROM outbounds WHERE tenantid=" . $outbound->getTenantID() . " AND outboundname='" .
				$outbound->getOutbound() . "'";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$outbound->setOutboundID( $objRow->outboundid );
			}
			
			$bReturn = true;
		}
		
		if ( $outbound->getOutboundID() > 0 ) {
			// for outboundsched
			$query = "DELETE FROM outboundsschedules WHERE outboundid=" . $outbound->getOutboundID();
			$resultSet = $this->mySQL->query($query);
			
			foreach ( $outbound->getOutboundScheds() as $outboundsched ) {
				$query = "INSERT INTO outboundsschedules (outboundid, outboundday, outbounddayenable, timestart, timeend) " .
					"VALUES (" . $outbound->getOutboundID() . ", " . $outboundsched->getDay() . ", " . $outboundsched->isEnable() . 
					", '" . $outboundsched->getTimeStart() . "', '" . $outboundsched->getTimeEnd() . "')";
				
				$resultSet = $this->mySQL->query($query);
			}
			
			// for outboundskills
			$query = "DELETE FROM outboundsskills WHERE outboundid=" . $outbound->getOutboundID();
			$resultSet  = $this->mySQL->query($query);
			
			foreach ( $outbound->getOutboundSkills() as $nSkill ) {
				$query = "INSERT INTO outboundsskills(skillid,outboundid) VALUES (" . $nSkill . ", " . $outbound->getOutboundID() . ")";
				
				$resultSet = $this->mySQL->query($query);
			}
			
			// four outboundwrapups
			$query = "DELETE FROM outboundswrapups WHERE outboundid=" . $outbound->getOutboundID();
			
			foreach ( $outbound->getOutboundWrapups() as $nWrapup ) {
				$query = "INSERT INTO outboundswrapups(wrapupid,outboundid) VALUES (" . $nWrapup . ", " . $outbound->getOutboundID() . ")";
				
				$resultSet = $this->mySQL->query($query);
			}
		}
		
		return $bReturn;
	}
	
	public function displayOutboundList($nTenantID,$nFilter) {
		$szReturn = '';
		$nCnt = 1;
		
		foreach ( $this->getOutbounds($nTenantID) as $outbound) {
			if (( $nFilter == 0 ) || ( $nFilter==1 && $outbound->getOutboundType() == 0 ) || ( $nFilter==2 && $outbound->getOutboundType() == 1 )) {
				$szReturn = $szReturn . '<label for="chkuser' . 
						$nCnt . '" onclick="onOutboundClick(' . $outbound->getOutboundID() . ');"><input type="checkbox" id="chkwrapup"' . $nCnt . 
						'" name="chkuser' . $nCnt . '" value="' . $outbound->getOutboundID() . '" /><b><font color="#009999">' . $outbound->getOutbound(). 
						'</font></label>';
			}
			$nCnt++;
		}
		
		return $szReturn;
	}
	
	private function getOutboundName($outboundID) {
		$szReturn = '';
		
		if ( $this->mySQL ) {
			$query = "SELECT * FROM outbounds WHERE outboundid=" . $outboundID;
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$szReturn = $objRow->outboundname;
			}
		}	
		
		return $szReturn;
	}
	
	private function isOutboundExist( $szOutbound, $ntenantID ) {
		$bReturn = false;
		
		if ( $this->mySQL ) {
			$query = "SELECT * FROM outbounds WHERE tenantid=" . $ntenantID . " AND outboundname='" . $szOutbound . "'";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$bReturn = true;
			}
		}
		
		return $bReturn;
	}
	
	public function getSkillsList($nTenantID) {
		$szReturn = '<table border="0" cellpadding="0" cellspacing="0">';
	
		if ( $this->mySQL ) {
			$query = "SELECT skillid, skill FROM skills WHERE tenantid=" .
				$nTenantID . " ORDER BY skill ASC";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			$nCnt = 1;
			
			while ( $objRow ) {
				$szChkName = 'chkskill' . $nCnt;
				$szRow = '<tr>';
				$szRow .= '<td width="30px"><input id="' . $szChkName . '" name="' . $szChkName . '" type="checkbox" value="' . $objRow->skillid . 
					'"></td>';
				$szRow .= '<td width="200px">' . $objRow->skill . '</td>';
				$szRow .= '</tr>';
				
				$szReturn .= $szRow;
				$objRow = $resultSet->fetch_object();
				$nCnt++;
			}
		}
		
		$szReturn .= '</table>';
		
		return $szReturn;
	}
	
	public function getWrapupsList($nTenantID) {
		$szReturn = '<table border="0" cellpadding="0" cellspacing="0">';
	
		if ( $this->mySQL ) {
			$query = "SELECT wrapupid, wrapup FROM wrapups WHERE tenantid=" .
				$nTenantID . " ORDER BY wrapup ASC";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			$nCnt = 1;
			
			while ( $objRow ) {
				$szChkName = 'chkwrapup' . $nCnt;
				$szRow = '<tr>';
				$szRow .= '<td width="30px"><input id="' . $szChkName . '" name="' . $szChkName . '" type="checkbox" value="' . $objRow->wrapupid . 
					'"></td>';
				$szRow .= '<td width="200px">' . $objRow->wrapup . '</td>';
				$szRow .= '</tr>';
				
				$szReturn .= $szRow;
				$objRow = $resultSet->fetch_object();
				$nCnt++;
			}
		}
		
		$szReturn .= '</table>';
		
		return $szReturn;
	}
}

?>
