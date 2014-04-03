<?php

require_once('errhandler.php');

class Queue {
	private $m_nQueueID = 0;
	private $m_szName = '';
	private $m_nTimeout = 15;
	private $m_nServiceLevel = 20;
	private $m_nPriority = 0;
	private $m_nWrapupTime = 0;
	private $m_arraySkills = array();
	private $m_arrayWrapups = array();
	private $m_nTenantID = 0;
	private $m_szTenantName = '';
	
	function __construct($nQueueID, $nTenantID, $szTenantName, $szName, $nTimeout, $nServiceLevel, 
						 $nPriority, $nWrapupTime) {
		$this->m_nQueueID = $nQueueID;
		$this->m_szName = $szName;
		$this->m_nTimeout = $nTimeout;
		$this->m_nServiceLevel = $nServiceLevel;
		$this->m_nPriority = $nPriority;
		$this->m_nWrapupTime = $nWrapupTime;
		$this->m_nTenantID = $nTenantID;
		$this->m_szTenantName = $szTenantName;
	}
	
	function __destruct() {
	}
	
	public function getTenantID() { return $this->m_nTenantID; }
	public function setTenantID($nTenantID) { $this->m_nTenantID = $nTenantID; }
	
	public function getTenantName() { return $this->m_szTenantName; }
	
	public function getQueueID() { return $this->m_nQueueID; }
	public function setQueueID($nQueueID) { $this->m_nQueueID = $nQueueID; }
	
	public function getQueueName() { return $this->m_szName; }
	public function setQueueName($szName) { $this->m_szName = $szName; }
	
	public function getTimeout() { return $this->m_nTimeout; }
	public function setTimeout($nTimeout) { $this->m_nTimeout = $nTimeout; }
	
	public function getServiceLevel() { return $this->m_nServiceLevel; }
	public function setServiceLevel($nSeviceLevel) { $this->m_nServiceLevel = $nSeviceLevel; }
	
	public function getPriority() { return $this->m_nPriority; }
	public function setPriority($nPriority) { $this->m_nPriority = $nPriority; }
	
	public function getWrapupTime() { return $this->m_nWrapupTime; }
	public function setWrapupTime($nWrapupTime) { $this->m_nWrapupTime = $nWrapupTime; }
	
	public function addSkill($skill) {
		array_push( $this->m_arraySkills, $skill);
	}
	
	public function getSkills() { return $this->m_arraySkills; }
	
	public function addWrapup($wrapup) {
		array_push( $this->m_arrayWrapups, $wrapup );
	}
	public function getWrapups() { return $this->m_arrayWrapups; }
}
?>