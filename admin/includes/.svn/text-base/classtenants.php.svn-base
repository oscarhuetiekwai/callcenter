<?php

require_once('config.php'); 
require_once('errhandler.php');
require_once('classtenant.php');

class Tenants {
	private $mySQL;
	private $m_szErrorMessage = '';
	
	function __construct() {
		$this->mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
	}
	
	function __destruct() {
		$this->mySQL->close();
	}
	
	public function getTenantsCount() {
		$nTenantsCount = 0;
		
		if ( $this->mySQL ) {
			$query = "SELECT count(*) AS totalcount FROM tenants";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$nTenantsCount = $objRow->totalcount;
			}
		}
		
		return $nTenantsCount;
	}
	
	public function getTenants($pageNo, $nLimit) {
		$arrTenants = array();
		
		if ( $this->mySQL ) {
			$query = "SELECT * FROM tenants ORDER BY name ASC LIMIT " .
				($pageNo * $nLimit) . ', ' . $nLimit . ';';
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			while ( $objRow ) {
				$tenant = new Tenant( $objRow->tenantid, $objRow->name, $objRow->contactperson,
					$objRow->officephone, $objRow->homephone, $objRow->mobilephone);
				
				array_push($arrTenants, $tenant);
				$objRow = $resultSet->fetch_object();
			}
		}
		
		return $arrTenants;
	}
	
	public function getTenantInfo($tenantID) {
		$tenant = NULL;
		
		if ( $this->mySQL ) {
			$query = "SELECT * FROM tenants WHERE tenantid=" . $tenantID;
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$tenant = new Tenant( $objRow->tenantid, $objRow->name, $objRow->contactperson,
					$objRow->officephone, $objRow->homephone, $objRow->mobilephone);
			}
		}
		
		return $tenant;
	}
	
	public function saveTenant($tenant) {
		$bReturn = false;
		
		if ( $tenant->getTenantName() == '' ) {
			$this->m_szErrorMessage = 'Please enter tenant name!!!';
			return false;
		}
		
		if ( $tenant->getTenantID() == 0 ) {
			// add tenant
			if ( $this->isTenantNameExist($tenant->getTenantName()) == false ) {
				if ( $this->mySQL ) {
					$query = "INSERT INTO tenants(name,contactperson,officephone,homephone,mobilephone) VALUES ('" .
							$tenant->getTenantName() . "', '" . $tenant->getContactPerson() . "', '" .
							$tenant->getOfficePhone() . "', '" . $tenant->getHomePhone() . "', '" .
							$tenant->getMobilePhone() . "');";
					$resultSet = $this->mySQL->query($query);
					
					$bReturn = true;
				} else {
					$this->m_szErrorMessage = 'Unable to connect to the database!!!';
				}
			} else {
				$this->m_szErrorMessage = $tenant->getTenantName() . ' is already in the database. Please change the tenant name!!!';
			}
		} else if ( $tenant->getTenantID() > 0 ) {
			// update tenant
			$bProceedWithUpdate = true;
			
			if ( $this->getTenantName( $tenant->getTenantID() ) != $tenant->getTenantName() ) {
				if ( $this->isTenantNameExist($tenant->getTenantName()) == true ) {
					$bProceedWithUpdate = false;
					$this->m_szErrorMessage = $tenant->getTenantName() . ' is already in the database. Please change the tenant name!!!';
				}
			}
			
			if ( $bProceedWithUpdate ) {
				if ( $this->mySQL ) {
					$query = "UPDATE tenants SET name='" . $tenant->getTenantName() . "',contactperson='" . 
						$tenant->getContactPerson() . "',officephone='" . $tenant->getOfficePhone() .
						"',homephone='" . $tenant->getHomePhone() . "',mobilephone='" . $tenant->getMobilePhone() .
						"' WHERE tenantid=" . $tenant->getTenantID();
						
					$resultSet = $this->mySQL->query($query);
					
					$bReturn = true;
				} else {
					$this->m_szErrorMessage = 'Unable to connect to the database!!!';
				}
			}
		}
		
		return $bReturn;
	}
	
	public function displayTenantsList() {
		$szReturn = '';
		$nCnt = 1;
		
		foreach ( $this->getTenants(0,99999) as $tenant) {
			$szReturn = $szReturn . '<label for="chktenant' . 
						$nCnt . '" onclick="onTenantClick(' . $tenant->getTenantID() . ');"><input type="checkbox" id="chktenant"' . $nCnt . 
						'" name="chktenant' . $nCnt . '" value="' . $tenant->getTenantID() . '" /><b><font color="#009999">' . $tenant->getTenantName(). 
						'</font></label>';
			$nCnt++;
		}
		
		return $szReturn;
	}
	
	private function isTenantNameExist($tenantName) {
		$bReturn = false;
		
		if ( $this->mySQL ) {
			$query = "SELECT * FROM tenants WHERE name='" . $tenantName . "';";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$bReturn = true;
			}
		}
		
		return $bReturn;
	}
	
	private function getTenantName($tenantID) {
		$szReturn = '';
		
		if ( $this->mySQL ) {
			$query = "SELECT name FROM tenants WHERE tenantid=" . $tenantID . ";";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$szReturn = $objRow->name;
			}
		}
		
		return $szReturn;
	}
	
	
	public function getLastErrorMessage() { return $this->m_szErrorMessage; }
	
}
?>