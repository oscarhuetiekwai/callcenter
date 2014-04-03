<?php

require_once('config.php'); 
require_once('errhandler.php');
require_once('classextension.php');

class Extensions {
	private $mySQL;
	private $m_szErrorMessage = '';
	
	function __construct() {
		$this->mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
	}
	
	function __destruct() {
		$this->mySQL->close();
	}
	
	public function getExtensionsCount() {
		$nExtensionsCount = 0;
		
		if ( $this->mySQL ) {
			$query = "SELECT count(*) AS totalcount FROM sipaccounts;";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$nExtensionsCount = $objRow->totalcount;
			}
		}
		
		return $nExtensionsCount;
	}
	
	public function getExtensionsCountByTenant($nTenantID) {
		$nExtensionsCount = 0;
		
		if ( $this->mySQL ) {
			$query = "SELECT count(*) AS totalcount FROM sipaccounts WHERE tenantid=" .$nTenantID . ";";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$nExtensionsCount = $objRow->totalcount;
			}
		}
		
		return $nExtensionsCount;
	}
	
	public function getExtensions($pageNo, $nLimit) {
		$arrExtensions = array();
		
		if ( $this->mySQL ) {
			$query = "SELECT id, sa.tenantid AS tenantid, IF(sa.tenantid=0,'SYSTEM',t.name) AS tenantname, " .
				"sa.name as name, secret, callerid FROM sipaccounts sa LEFT JOIN tenants t ON " .
				"t.tenantid=sa.tenantid ORDER BY sa.name ASC LIMIT " .
				($pageNo * $nLimit) . ', ' . $nLimit . ';';
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			while ( $objRow ) {
				$extension = new Extension($objRow->id,$objRow->tenantid,$objRow->tenantname,$objRow->name,
										   $objRow->callerid,$objRow->secret);
				
				if ( $extension ) array_push($arrExtensions, $extension);
				$objRow = $resultSet->fetch_object();
			}
		}
		
		return $arrExtensions;
	}
	
	public function getExtensionsByTenant($ntenantID, $pageNo, $nLimit) {
		$arrExtensions = array();
		
		if ( $this->mySQL ) {
			$query = "SELECT id, sa.tenantid AS tenantid, IF(sa.tenantid=0,'SYSTEM',t.name) AS tenantname, " .
				"sa.name as name, secret, callerid FROM sipaccounts sa LEFT JOIN tenants t ON " .
				"t.tenantid=sa.tenantid WHERE sa.tenantid = " . $ntenantID . " ORDER BY sa.name ASC LIMIT " .
				($pageNo * $nLimit) . ', ' . $nLimit . ';';
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			while ( $objRow ) {
				$extension = new Extension($objRow->id,$objRow->tenantid,$objRow->tenantname,$objRow->name,
										   $objRow->callerid,$objRow->secret);
				
				if ( $extension ) array_push($arrExtensions, $extension);
				$objRow = $resultSet->fetch_object();
			}
		}
		
		return $arrExtensions;
	}
	
	public function saveExtension($extension) {
		$bReturn = false;
		
		if ( $extension ) {
			if ( $extension->getExtensionName() == '' ) {
				$this->m_szErrorMessage = 'Please enter extension name!!!';
				return $bReturn;
			} else if ( $extension->getSecret() == '' ) {
				$this->m_szErrorMessage = 'Please enter extension secret!!!';
				return $bReturn;
			}
		} else {
			$this->m_szErrorMessage = 'Uninitialize extension class!!!';
			return $bReturn;
		}
		
		if ( !$this->mySQL ) {
			$this->m_szErrorMessage = 'Error connecting to the database!!!';
			return $bReturn;
		}
		
		if ( $extension->getExtensionID() > 0 ) {
			// this is for update
			if ( $this->getExtensionName( $extension->getExtensionID() ) != $extension->getExtensionName() ) {
				if ( $this->isExtensionNameExist( $extension->getExtensionName() ) ) {
					$this->m_szErrorMessage = 'Extension name "' . $extension->getExtensionName() . '" is already in the database. Please change the extension name!!!';
					return $bReturn;
				}
			}
			
			$query = "UPDATE sipaccounts SET name='" . $extension->getExtensionName() . "', tenantid=" .
					$extension->getTenantID() . ", secret='" . $extension->getSecret() . "', defaultuser='" .
					$extension->getExtensionName() . "', context='default', callerid='" . $extension->getCallerID() .
					"' WHERE id=" . $extension->getExtensionID();
			
			$resultSet = $this->mySQL->query($query);
			
			$bReturn = true;
			

		} else {
			// this is for adding new items
			if ( $this->isExtensionNameExist( $extension->getExtensionName() ) ) {
				$this->m_szErrorMessage = 'Extension name "' . $extension->getExtensionName() . '" is already in the database. Please change the extension name!!!';
				return $bReturn;
			}
			
			$query = "INSERT INTO sipaccounts (name, tenantid, secret, defaultuser, context, callerid) VALUES ('" .
					$extension->getExtensionName() . "', " . $extension->getTenantID() . ", '" .  $extension->getSecret() .
					"', '" . $extension->getExtensionName() . "', 'default', '" . $extension->getCallerID() . "');" ;
			
			$resultSet = $this->mySQL->query($query);
			
			$bReturn = true;
			
		}
		
		return $bReturn;
	}
	
	public function displayExtensionsList($nExtensionID) {
		$szReturn = '';
		$nCnt = 1;
		
		foreach ( $this->getExtensions(0,999999) as $extension) {
			$szReturn = $szReturn . '<label for="chkuser' . 
						$nCnt . '" onclick="onExtensionClick(' . $extension->getExtensionID() . ');"><input type="checkbox" id="chkextension"' . $nCnt . 
						'" name="chkuser' . $nCnt . '" value="' . $extension->getExtensionID() . '" /><b><font color="#009999">' . $extension->getExtensionName(). 
						'</font></label>';
			$nCnt++;
		}
		
		return $szReturn;
	}
	
	public function getExtensionInfo($extensionID) {
		$extension = NULL;
		
		if ( $this->mySQL ) {
			$query = "SELECT id, sa.tenantid AS tenantid, IF(sa.tenantid=0,'SYSTEM',t.name) AS tenantname, " .
				"sa.name as name, secret, callerid FROM sipaccounts sa LEFT JOIN tenants t ON " .
				"t.tenantid=sa.tenantid WHERE sa.id=" . $extensionID . ";";
				
		
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$extension = new Extension($objRow->id,$objRow->tenantid,$objRow->tenantname,$objRow->name,
										   $objRow->callerid,$objRow->secret);
			}
		}
		
		return $extension;
	}
	
	public function getTenantName($tenantID) {
		$szTenantName = '';
		
		if ( $tenantID == 0 ) {
			$szTenantName = 'SYSTEM';
		} else if ( $this->mySQL ) {
			$query = "SELECT name FROM tenants WHERE tenantid=" . $tenantID;
		
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$szTenantName = $objRow->name;
			}
		}
		
		return $szTenantName;
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
	
		/*public function getTenantList($selTenantID) {
		$szReturn = '';
		if ( $this->mySQL ) {
			$query = "SELECT tenantid, tenantname FROM tenants ORDER BY name ASC;";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			$szReturn = '<select id="hdnTenantName" name="hdnTenantName" width="180" style="width:180px">';
			while ( $objRow ) {
				$szReturn .= '<option value="' . $objRow->tenantid . '">' . $objRow->tenantname . '</option>';
				$objRow = $resultSet->fetch_object();
			}
			$szReturn .= '</select>';
		}
		
		return $szReturn;
	}*/
	
	public function getLastErrorMessage() { return $this->m_szErrorMessage; }
	
	private function getExtensionName($extensionID) {
		$szReturn = '';
		
		if ( $this->mySQL ) {
			$query = "SELECT name FROM sipaccounts WHERE id=" . $extensionID . ";";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$szReturn = $objRow->name;
			}
		}
		
		return $szReturn;
	}
	
	/*private function db_createlist($linkID,$default,$query,$blank)
		{
			  if($blank)
			  {
				  print("<option select value=\"0\">$blank</option>");
			  }
		  	  $query = "SELECT tenantid, name FROM tenants;"; 
			  $resultID = pg_exec($linkID,$query);
			  $num       = pg_numrows($resultID); 
			  
			  for ($i=0;$i<$num;$i++)
			  {
				  $row = pg_fetch_row($resultID,$i);
				  
				  if($row[0]==$default)$dtext = "selected";
				  else $dtext = "";
			  
				  print("<option $dtext value=\"$row[0]\">$row[1]</option>");
			  }
		 }*/
	private function isExtensionNameExist($extensionName) {
		$bReturn = false;
		
		if ( $this->mySQL ) {
			$query = "SELECT * FROM sipaccounts WHERE name='" . $extensionName . "';";
			
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