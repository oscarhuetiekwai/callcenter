<?php

class ResponseLogin {
	private $m_nResponseCode = -1;
	private $m_szSessionID = '';
	private $m_szLastName = '';
	private $m_szFirstName = '';
	private $m_szTenant = '';
	private $m_nUserID = 0;
	private $m_nTenantID = 0;
	
	public function __construct($nResponseCode, $szSessionID, $szLastName,
		$szFirstName, $szTenant, $nTenantID, $nUserID ) {
		$this->m_nResponseCode = $nResponseCode;
		$this->m_szSessionID = $szSessionID;
		$this->m_szLastName = $szLastName;
		$this->m_szFirstName = $szFirstName;
		$this->m_szTenant = $szTenant;
		$this->m_nTenantID = $nTenantID;
		$this->m_nUserID = $nUserID;
	}
	
	public function getResponseCode() { return $this->m_nResponseCode; }
	public function getSessionID() { return $this->m_szSessionID; }
	public function getFirstName() { return $this->m_szFirstName; }
	public function getLastName() { return $this->m_szLastName; }
	public function getTenant() { return $this->m_szTenant; }
	public function getTenantID() { return $this->m_nTenantID; }
	public function getUserID() { return $this->m_nUserID; }
}

class ServerResponse {
	private $m_arrResponse = NULL;
	private $m_szResponse = '';
	
	public function __construct($szResponse) {
		$this->m_szResponse = $szResponse;
		$this->m_arrResponse = explode(':', $szResponse );
	}
	
	public function __destruct() {}
	
	public function getResponseObject() {
		$responseObject = NULL;
		
		if ( isset($this->m_arrResponse) ) {
			if ( strcmp( $this->m_arrResponse[0], 'login' ) == 0 ) {
				$responseObject = new ResponseLogin( $this->m_arrResponse[1], 
					$this->m_arrResponse[2], $this->m_arrResponse[3],
					$this->m_arrResponse[4], $this->m_arrResponse[5],
					$this->m_arrResponse[6], $this->m_arrResponse[7]);
			} else /*if ( strcmp( $this->m_arrResponse[0], 'keepalive') == 0 ) */ {
				$responseObject = $this->m_szResponse;
                        }
		}
		
		return $responseObject;
	}

    public function getRawResponse() { return $this->m_szResponse; }
}

?>