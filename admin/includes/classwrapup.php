<?php

require_once('errhandler.php');

class Wrapup{
	private $m_nWrapupID = 0;
	private $m_nTenantID = 0;
	private $m_szWrapup = '';
	private $m_szDescription = '';
	
	function __construct($nWrapupID,$nTenantID,$szWrapup,$szDescription) {
		$this->m_nWrapupID = $nWrapupID;
		$this->m_nTenantID = $nTenantID;
		$this->m_szWrapup = $szWrapup;
		$this->m_szDescription = $szDescription;
	}
	
	public function getWrapupID() { return $this->m_nWrapupID; }
	public function setWrapupID($nWrapupID) { $this->m_nWrapupID = $nWrapupID; }
	
	public function getTenantID() { return $this->m_nTenantID; }
	public function setTenantID( $nTenantID ) { $this->m_nTenantID = $nTenantID; }
	
	public function getWrapup() { return $this->m_szWrapup; }
	public function setWrapup( $szWrapup ) { $this->m_szWrapup = $szWrapup; }
	
	public function getDescription() { return $this->m_szDescription; }
	public function setDescription($szDescription) { $this->m_szDescription = $szDescription; }
}

?>