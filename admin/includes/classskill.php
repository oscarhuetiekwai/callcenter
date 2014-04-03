<?php

require_once('errhandler.php');

class Skill {
	private $m_nSkillID = 0;
	private $m_nTenantID = 0;
	private $m_szTenant = '';
	private $m_szSkill = '';
	private $m_szDesc = '';
	
	function __construct($nSkillID, $nTenantID, $szTenant, $szSkill, $szDesc) {
		$this->m_nSkillID = $nSkillID;
		$this->m_nTenantID = $nTenantID;
		$this->m_szSkill = $szSkill;
		$this->m_szDesc = $szDesc;
		$this->m_szTenant = $szTenant;
	}
	
	function __destruct() {
	}
	
	public function getSkillID() { return $this->m_nSkillID; }
	public function setSkillID($nSkillID) { $this->m_nSkillID = $nSkillID; }
	
	public function getTenantID() { return $this->m_nTenantID; }
	public function setTenantID($nTenantID) { $this->m_nTenantID = $nTenantID; }
	
	public function getTenant() { return $this->m_szTenant; }
	public function setTenant($szTenant) { $this->m_szTenant = $szTenant; }
	
	public function getSkill() { return $this->m_szSkill; }
	public function setSkill($szSkill) { $this->m_szSkill = $szSkill; }
	
	public function getSkillDesc() { return $this->m_szDesc; }
	public function setSkillDesc($szDesc) { $this->m_szDesc = $szDesc; }
}

?>