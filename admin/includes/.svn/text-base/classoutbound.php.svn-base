<?php
require_once('errhandler.php');

class OutboundSched {
	private $m_nDay;
	private $m_bIsEnable = '0';
	private $m_tmStart = '00:00:00';
	private $m_tmEnd = '00:00:00';
	
	function __construct($nDay,$bIsEnable,$tmStart,$tmEnd) {
		$this->m_nDay = $nDay;
		$this->m_bIsEnable = $bIsEnable;
		$this->m_tmStart = $tmStart;
		$this->m_tmEnd = $tmEnd;
	}
	
	public function getDay() { return $this->m_nDay; }
	public function setDay($nDay) { $this->m_nDay = $nDay; }
	
	public function isEnable() { return $this->m_bIsEnable; }
	public function setEnable($bEnable) { $this->m_bIsEnable = $bEnable; }
	
	public function getTimeStart() { return $this->m_tmStart; }
	public function setTimeStart($tmStart) { $this->m_tmStart = $tmStart; }
	
	public function getTimeEnd() { return $this->m_tmEnd; }
	public function setTimeEnd($tmEnd) { $this->m_tmEnd = $tmEnd; }
								  
}

class Outbound {
	private $m_nOutboundID = 0;
	private $m_nTenantID = 0;
	private $m_szOutbound = '';
	private $m_nOutboundType = 0;
	private $m_dtOutboundStart = '0000-00-00 00:00:00';
	private $m_dtOutboundEnd  = '0000-00-00 00:00:00';
	private $m_arrayOutSched = array();
	private $m_arrayWrapup = array();
	private $m_arraySkills = array();
	
	function __construct($nOutboundID,$nTenantID,$szOutbound,$nOutboundType,$dtOutboundStart,$dtOutboundEnd) {
		$this->m_nOutboundID = $nOutboundID;
		$this->m_nTenantID = $nTenantID;
		$this->m_szOutbound = $szOutbound;
		$this->m_nOutboundType = $nOutboundType;
		$this->m_dtOutboundStart = $dtOutboundStart;
		$this->m_dtOutboundEnd = $dtOutboundEnd;
	}
	
	public function getOutboundID() { return $this->m_nOutboundID; }
	public function setOutboundID($nOutboundID) { $this->m_nOutboundID = $nOutboundID; }
	
	public function getTenantID() { return $this->m_nTenantID; }
	public function setTenantID($nTenantID) { $this->m_nTenantID = $nTenantID; }
	
	public function getOutbound() { return $this->m_szOutbound; }
	public function setOutbound($szOutbound) { $this->m_szOutbound = $szOutbound; }
	
	public function getOutboundType() { return $this->m_nOutboundType; }
	public function setOutboundType($nOutboundType) { $this->m_nOutboundType = $nOutboundType; }
	
	public function getOutboundStart() { return $this->m_dtOutboundStart; }
	public function setOutboundStart($dtOutboundStart) { $this->m_dtOutboundStart = $dtOutboundStart; }
	
	public function getOutboundEnd() { return $this->m_dtOutboundEnd; }
	public function setOutboundEnd($dtOutboundEnd) { $this->m_dtOutboundEnd = $dtOutboundEnd; }
	
	public function getOutboundScheds() { return $this->m_arrayOutSched; }
	public function addOutboundSched($outboundSched) {
		array_push( $this->m_arrayOutSched, $outboundSched);
	}
	
	public function getOutboundWrapups() { return $this->m_arrayWrapup; }
	public function addOutboundWrapup($wrapupID) {
		array_push( $this->m_arrayWrapup, $wrapupID );
	}
	
	public function getOutboundSkills() { return $this->m_arraySkills; }
	public function addOutboundSkill($skillID) {
		array_push( $this->m_arraySkills, $skillID );		
	}
}

?>