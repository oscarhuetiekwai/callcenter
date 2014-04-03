<?php

require_once('errhandler.php');

class Extension {
	private $m_nExtensionID = 0;
	private $m_nTenantID = 0;
	private $m_szTenantName = 'SYSTEM';
	private $m_szExtensionName = '';
	private $m_szCallerID = '';
	private $m_szSecret = '';
	
	function __construct($nExtensionID, $nTenantID, $szTenantName, $szExtensionName, $szCallerID, $szSecret) {
		$this->m_nExtensionID = $nExtensionID; 
		$this->m_nTenantID = $nTenantID;
		$this->m_szExtensionName = $szExtensionName;
		$this->m_szCallerID = $szCallerID;
		$this->m_szSecret = $szSecret; 
		$this->m_szTenantName = $szTenantName;
	}
						 
	function __destruct() {
	}
	
	public function getExtensionID() { return $this->m_nExtensionID; }
	public function setExtensionID($nExtensionID) { $this->m_nExtensionID = $nExtensionID; }
	
	public function getTenantID() { return $this->m_nTenantID; }
	public function setTenantID($nTenantID) { $this->m_nTenantID = $nTenantID; }
	
	public function getExtensionName() { return $this->m_szExtensionName; }
	public function setExtensionName($szExtensionName) { $this->m_szExtensionName = $szExtensionName; }
	
	public function getCallerID() { return $this->m_szCallerID; }
	public function setCallerID($szCallerID) { $this->m_szCallerID = $szCallerID; }
	
	public function getSecret() { return $this->m_szSecret; }
	public function setSecret($szSecret) { $this->m_szSecret = $szSecret; }
	
	public function getTenantName() { return $this->m_szTenantName; }
	public function setTenantName($szTenantName) { $this->m_szTenantName = $szTenantName; }
}

?>