<?php

require_once('config.php'); 
require_once('errhandler.php');
require_once('classskill.php');

class Skills {
	private $mySQL;
	private $m_szErrorMessage = '';
	
	function __construct() {
		$this->mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
	}
	
	function __destruct() {
		$this->mySQL->close();
	}
	
	public function getSkillsCount($tenantID) {
		$nSkillsCount = 0;
		
		if ( $this->mySQL ) {
			$query = "SELECT count(*) AS totalcount FROM skills";
			
			if ( $tenantID != "0" ) {
				$query = "SELECT count(*) AS totalcount FROM skills WHERE tenantid=" . $tenantID . ";";
			}
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$nSkillsCount = $objRow->totalcount;
			}
		}
		
		return $nSkillsCount;
	}
	
	public function getSkills($tenantID, $pageNo, $nLimit) {
		$arrSkills = array();
		
		if ( $this->mySQL ) {
			$query = "SELECT * FROM skills s LEFT JOIN tenants t ON s.tenantid = t.tenantid WHERE s.skilldbstatus='A' ORDER BY s.skill ASC LIMIT "
				. ($pageNo * $nLimit) . ', ' . $nLimit . ';';
				
			if ( $tenantID != "0" ) {
				$query = "SELECT * FROM skills  s LEFT JOIN tenants t ON s.tenantid = t.tenantid WHERE s.skilldbstatus='A' AND s.tenantid=" .
					$tenantID . " ORDER BY s.skill ASC LIMIT " . ($pageNo * $nLimit) . ', ' . $nLimit . ';';
			}
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			while ( $objRow ) {
				$skill = new Skill($objRow->skillid,$objRow->tenantid,$objRow->name,$objRow->skill,
								   $objRow->description);				
				
				if ( $skill ) array_push($arrSkills, $skill);
				
				$objRow = $resultSet->fetch_object();
			}
		}
		
		return $arrSkills;
	}
	
	public function getSkillInfo($skillID) {
		$skill = NULL;
		
		if ( $this->mySQL ) {
			$query = "SELECT * FROM skills s LEFT JOIN tenants t ON s.tenantid = t.tenantid WHERE  s.skillid=" . 
				$skillID;
		
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$skill = new Skill($objRow->skillid,$objRow->tenantid,$objRow->name,$objRow->skill,
								   $objRow->description);				
			}
		}
		
		return $skill;
	}
	
	public function saveSkill($skill) {
		$bReturn = false;
		
		if ( $skill ) {
			if ( $skill->getSkill() == '' ) {
				$this->m_szErrorMessage = 'Please enter skill name!!!';
				return $bReturn;
			} 
		} else {
			$this->m_szErrorMessage = 'Uninitialize skill class!!!';
			return $bReturn;
		}
		
		if ( !$this->mySQL ) {
			$this->m_szErrorMessage = 'Error connecting to the database!!!';
			return $bReturn;
		}
		
		if ( $skill->getSkillID() > 0 ) {
			// this is for update
			if ( $this->getSkillName( $skill->getSkillID() ) != $skill->getSkill() ) {
				if ( $this->isSkillExist( $skill->getTenantID(), $skill->getSkill() ) ) {
					$this->m_szErrorMessage = 'Skill "' . $skill->getSkill() . '" is already in the database. Please change the extension name!!!';
					return $bReturn;
				}
			}
			
			$query = "UPDATE skills SET tenantid=" . $skill->getTenantID() . ", skill='" .
				$skill->getSkill() . "', description='" . $skill->getSkillDesc() . 
				"' WHERE skillid=" . $skill->getSkillID();
			
			$resultSet = $this->mySQL->query($query);
			
			$bReturn = true;
			

		} else {
			// this is for adding new items
			if ( $this->isSkillExist( $skill->getTenantID(), $skill->getSkill() ) ) {
				$this->m_szErrorMessage = 'Skill "' . $skill->getSkill() . '" is already in the database. Please change the extension name!!!';
				return $bReturn;
			}
			
			$query = "INSERT INTO skills (tenantid, skill, description) VALUES (" . $skill->getTenantID() .
				", '" . $skill->getSkill() . "', '" . $skill->getSkillDesc() . "');";
			
			$resultSet = $this->mySQL->query($query);
			
			$bReturn = true;
			
		}
		
		return $bReturn;
	}
	
	public function getAllTenantsOption($selTenantID) {
		if ( $this->mySQL ) {
			$query = "SELECT tenantid, name FROM tenants ORDER BY name ASC;";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $selTenantID == 0 ){
				echo '<option value="0" SELECTED>SYSTEM</option>';
			} else {
				echo '<option value="0">SYSTEM</option>';
			}
			
			while ( $objRow ) {
				if ( $selTenantID == $objRow->tenantid ) {
					echo '<option value="' . $objRow->tenantid . '" SELECTED>' . $objRow->name . '</option>';
				} else  {
					echo '<option value="' . $objRow->tenantid . '" >' . $objRow->name . '</option>';
				}
				$objRow = $resultSet->fetch_object();
			}
		}
	}
	
	public function displaySkillList($tenantID) {
		$szReturn = '';
		$nCnt = 1;
		
		foreach ( $this->getSkills($tenantID,0,99999) as $skill) {
			$szReturn = $szReturn . '<label for="chkuser' . 
						$nCnt . '" onclick="onSkillClick(' . $skill->getSkillID() . ');"><input type="checkbox" id="chkskill' . $nCnt . 
						'" name="chkskill" value="' . $skill->getSkillID() . '" /><b><font color="#009999">' . $skill->getSkill().
						'</font></label>';
			$nCnt++;
		}
		
		return $szReturn;
	}

        public function deleteSkill($skillID) {
                if ( $this->mySQL ) {
                        $query = "UPDATE skills set skilldbstatus='D' WHERE skillid=" . $skillID . ";";
                        $resultSet = $this->mySQL->query( $query );

                        return $resultSet;
                }
        }
	
	public function getLastErrorMessage() { return $this->m_szErrorMessage; }
	
	private function getSkillName($skillID) {
		$szSkill = '';
		
		if ( $this->mySQL ) {
			$query = "SELECT skill FROM skills WHERE skillid=" . $skillID;
		
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$szSkill = $objRow->skill;
			}
		}
		
		return $szSkill;
	}
	
	private function isSkillExist($tenantID,$skillName) {
		$bReturn = false;
		
		if ( $this->mySQL ) {
			$query = "SELECT * FROM skills WHERE skilldbstatus='A' AND tenantid=" . $tenantID . " AND skill='" . $skillName . "';";
			
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