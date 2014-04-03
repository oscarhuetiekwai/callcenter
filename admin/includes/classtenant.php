<?php

require_once('errhandler.php');

class Tenant {
	private $m_nTenantID = 0;
	private $m_szTenantName = '';
	private $m_szContactPerson = '';
	private $m_szOfficePhone = '';
	private $m_szHomePhone = '';
	private $m_szMobilePhone = '';
	private $m_dtRegistered = '';
	private $m_bUpdated = false;
	
	public function __construct($nTenantID, $szTenantName, $szContactPerson, $szOfficePhone, 
		$szHomePhone, $szMobilePhone ) {
		$this->m_nTenantID = $nTenantID;
		$this->m_szTenantName = $szTenantName;
		$this->m_szContactPerson = $szContactPerson;
		$this->m_szOfficePhone = $szOfficePhone;
		$this->m_szHomePhone = $szHomePhone;
		$this->m_szMobilePhone = $szMobilePhone;
	}
	
	public function __destruct() {
	
	}
	
	public function getTenantID() { return $this->m_nTenantID; }
	public function setTenantID($nTenantID) { $this->m_nTenantID = $nTenantID; }
	
	public function getTenantName() { return $this->m_szTenantName; }
	public function setTenantName($szTenantName) {
		if ( $this->m_szTenantName != $szTenantName ) {
			$this->m_bUpdated = true;
			$this->m_szTenantName = $szTenantName;
		}
	}
	
	public function getContactPerson() { return $this->m_szContactPerson; }
	public function setContactPerson($szContactPerson) {
		if ( $this->m_szContactPerson != $szContactPerson ) {
			$this->m_bUpdated = true;
			$this->m_szContactPerson = $szContactPerson;
		}
	}
	
	public function getOfficePhone() { return $this->m_szOfficePhone; }
	public function setOfficePhone($szOfficePhone) {
		if ( $this->m_szOfficePhone != $szOfficePhone ) {
			$this->m_bUpdated = true;
			$this->m_szOfficePhone = $szOfficePhone;
		}	
	}
	
	public function getHomePhone() { return $this->m_szHomePhone; }
	public function setHomePhone($szHomePhone) {
		if ( $this->m_szHomePhone != $szHomePhone ) {
			$this->m_bUpdated = true;
			$this->m_szHomePhone = $szHomePhone;
		}	
	}
	
	public function getMobilePhone() { return $this->m_szMobilePhone; }
	public function setMobilePhone($szMobilePhone) {
		if ( $this->m_szMobilePhone != $szMobilePhone ) {
			$this->m_bUpdated = true;
			$this->m_szMobilePhone = $szMobilePhone;
		}
	}
}

?>