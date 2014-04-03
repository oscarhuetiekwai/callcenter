<?php

require_once('errhandler.php');

class Call {
	private $m_nCallID = 0;
	private $m_nTenantID = 0;
	private $m_szCallerID = 0;
	private $m_szUsername = '';
	private $m_szUserExten = '';
	private $m_szQueue = '';
	private $m_szTimestampdate = '%e-%m-%Y';
	private $m_szTimestamptime = '%H:%i:%s';
	private $m_szCallDuration = '';
	private $m_szUserID = 0;
	private $m_nWrapupID = 0;
	
	
	function __construct($nCallID, $nTenantID, $szCallerID, $szUsername, $szUserExten, $szQueue, $szTimestampdate, $szTimestamptime, $szCallDuration, $szUserID) {
		$this->m_nCallID = $nCallID; 
		$this->m_nTenantID = $nTenantID;
		$this->m_szCallerID = $szCallerID;
		$this->m_szUsername = $szUsername;
		$this->m_szUserExten = $szUserExten;
		$this->m_szQueue = $szQueue;
		$this->m_szTimestampdate = $szTimestampdate;
		$this->m_szTimestamptime = $szTimestamptime;
		$this->m_szCallDuration = $szCallDuration;
		$this->m_szUserID = $szUserID; 
		//$this->m_nWrapupID = $nWrapupID;		
	}
						 
	function __destruct() {
	}
	
	public function getCallID() { return $this->m_nCallID; }
	public function setCallID($nCallID) { $this->m_nCallID = $nCallID; }
	
	public function getTenantID() { return $this->m_nTenantID; }
	public function setTenantID($nTenantID) { $this->m_nTenantID = $nTenantID; }
	
	public function getCallerID() { return $this->m_szCallerID; }
	public function setCallerID($szCallerID) { $this->m_szCallerID = $szCallerID; }
	
	public function getUsername() { return $this->m_szUsername; }
	public function setUsername($szUsername) { $this->m_szUsername = $szUsername; }
	
	public function getUserExten() { return $this->m_szUserExten; }
	public function setUserExten($szUserExten) { $this->m_szUserExten = $szUserExten; }
	
	public function getQueue() { return $this->m_szQueue; }
	public function setQueue($szQueue) { $this->m_szQueue = $szQueue; }
	
	public function getTimestampdate() { return $this->m_szTimestampdate; }
	public function setTimestampdate($szTimestamp) { $this->m_szTimestampdate = $szTimestampdate; }
	
	public function getTimestamptime() { return $this->m_szTimestamptime; }
	public function setTimestamptime($szTimestamptime) { $this->m_szTimestamptime = $szTimestamptime; }
	
	public function getCallDuration() { return $this->m_szCallDuration; }
	public function setCallDuration($szCallDuration) { $this->m_szCallDuration = $szCallDuration; }
	
	public function getUserID() { return $this->m_szUserID; }
	public function setUser($szUserID) { $this->m_szUserID = $szUserID; }
	
	public function getWrapupID() { return $this->m_nWrapupID; }
	public function setWrapup($nWrapupID) { $this->m_nWrapupID = $nWrapupID; }
	
}

?>