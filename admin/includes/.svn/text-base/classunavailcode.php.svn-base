<?php

require_once('errhandler.php');

class UnavailCode {
	private $m_nUserStatusID = 0;
	private $m_nTenantID = 0;
	private $m_szUserStatus = '';
	private $m_nProductive = 0;
	
	function __construct($nUserStatusID, $nTenantID, $szUserStatus, $nProductive) {
		$this->m_nUserStatusID = $nUserStatusID;
		$this->m_nTenantID = $nTenantID;
		$this->m_szUserStatus = $szUserStatus;
		$this->m_nProductive = $nProductive;
	}
	
	function __destruct() {
	}
	
	public function getUserStatusID() { return $this->m_nUserStatusID; }
	public function setUserStatusID($nUserStatusID) { $this->m_nUserStatusID = $nUserStatusID; }
	
	public function getTenantID() { return $this->m_nTenantID; }
	public function setTenantID($nTenantID) { $this->m_nTenantID = $nTenantID; }
	
	public function getUserStatus() { return $this->m_szUserStatus; }
	public function setUserStatus($szUserStatus) { $this->m_szUserStatus = $szUserStatus; }
	
	public function getProductive() { return $this->m_nProductive; }
	public function setProductive($nProductive) { $this->m_nProductive = $nProductive; }
	
}

?>