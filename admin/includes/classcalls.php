<?php

require_once('config.php'); 
require_once('errhandler.php');
require_once('classcall.php');

class Calls {
	private $mySQL;
	private $m_szErrorMessage = '';
	
	function __construct() {
		$this->mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
	}
	
	function __destruct() {
		$this->mySQL->close();
	}
	

	
	public function getCalls($nTenantID) {
		$arrCalls = array();
	
	
	if(isset($_POST['username']) || $wrapup = isset($_POST['wrapup']) || $ext = isset($_POST['ext'])){
		 if( $this->mySQL ) {
			//$query = "SELECT c.callid,c.callerid,c.tenantid,u.username,c.userexten,c.queue,DATE_FORMAT(c.timestamp, '%d/%m/%y') AS      		timestamp,DATE_FORMAT(c.timestamp, '%H:%i:%s') AS timestamp2,c.callduration,c.userid,w.wrapup FROM callstatus c,users       		u,wrapups w WHERE c.userid=u.userid AND c.wrapupid=w.wrapupid AND ((u.username LIKE '%".$_POST['username']."%') OR (w.wrapup LIKE '%".$_POST['wrapup']."%'))  ORDER BY c.callid ASC LIMIT 0,6;";
//			

		$query = "SELECT c.callid,c.callerid,c.tenantid,u.username,c.userexten,c.queue,DATE_FORMAT(c.timestamp, '%d/%m/%y')AS timestamp1,DATE_FORMAT(c.timestamp, '%H:%i:%s') AS timestamp2,c.callduration,c.userid FROM callstatus c,users u WHERE c.userid=u.userid AND (u.username LIKE '%".$_POST['username']."%' OR c.userexten LIKE '%".$_POST['ext']."%') ORDER BY c.callid ASC LIMIT 0,6";
		
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			while ( $objRow ) {
				$call = new Call($objRow->callid,$objRow->tenantid,$objRow->callerid,$objRow->username,$objRow->userexten, 
								 $objRow->queue, $objRow->timestamp1,$objRow->timestamp2, $objRow->callduration,$objRow->userid);
				
				array_push( $arrCalls, $call );
				$objRow = $resultSet->fetch_object();
			}
		}}
		
		return $arrCalls;
	}
	
	/*public function getCallInfo($username,$queue) {
		$callInfo = NULL;
		$username = $_SESSION['username'] ;
		$queue = $_SESSION['queue'] ;
		
		
		
		if ( $this->mySQL ) {
			$query = "SELECT c.callid,c.tenantid,c.callerid,u.username,c.userexten,c.queue,c.callduration, DATE_FORMAT(c.timestamp, '%d/%m/%y') AS timestamp,DATE_FORMAT(c.timestamp, '%H:%i:%s') AS timestamp2 FROM callstatus c,users u WHERE c.userid=u.userid AND u.username LIKE '%" . $username."%' AND c.queue LIKE '%".$queue."% ORDER BY c.callid '";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$callInfo = new Call($objRow->callid,$objRow->tenantid,$objRow->callerid,$objRow->username,$objRow->userexten, 
								 $objRow->queue, $objRow->timestamp,$objRow->timestamp2, $objRow->callduration, $objRow->userid);
				
				
			}
		}
		
		return $callInfo;
	}*/
	
	
	public function displayCallList($nTenantID) {
		$szReturn = '';
		$nCnt = 1;
		$play='';
		
		
		foreach ( $this->getCalls($nTenantID) as $call) {
			
			
/*			$szReturn = $szReturn . '<label for="chkuser' . 
						$nCnt . '" onclick="onCallClick();">*/
/*			$szReturn = $szReturn .*/
			$szReturn .='<tr class="qm">';
			$szReturn .=('<td id="gc"'.$nCnt.'><input type="checkbox" name="check_list" ></td>');
			$szReturn .=('<td id="gc1"'.$nCnt.'>'.$call->getTimestampdate().'</td>');
			$szReturn .=('<td id="gc2"'.$nCnt.'>'.$call->getTimestamptime().'</td>');
			$szReturn .=('<td id="gc3"'.$nCnt.'>'.$call->getUsername().'</td>');
			$szReturn .=('<td id="gc4"'.$nCnt.'>'.$call->getUserExten().'</td>');
			$szReturn .=('<td id="gc5"'.$nCnt.'>'.$call->getCallerID().'</td>');
			$szReturn .=('<td id="gc6"'.$nCnt.'>'.$call->getQueue().'</td>');
			$szReturn .=('<td id="gc7"'.$nCnt.'>'.$call->getCallDuration().'</td>');
			$szReturn .=('<td id="gc8"'.$nCnt.'>&nbsp;</td>');
						
			$szReturn .='</tr></label>';
			$nCnt++;
			
			/*$play = '<table align="left" width="650"><a href="download.php?f=download.zip"><a/><tr><td align="center"><embed src="./audio file/Mohammed_Saeed_al-Sahaf.gsm" width="300" height="40" autostart="false" loop="FALSE"> </embed></td></tr></table>';*/
		}
		
		return $szReturn.$play;
		
	}
	
	
public function getAllWrapupsOption($selTenantID) {
		if ( $this->mySQL ) {
			$query = "SELECT tenantid, wrapupid,wrapup FROM wrapups ;";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $selTenantID == 0 ){
				echo '<option value="0" SELECTED>&nbsp;</option>';
			} else {
				echo '<option value="0">&nbsp;</option>';
			}
			
			while ( $objRow ) {
				if ( $selTenantID == $objRow->tenantid ) {
					echo '<option value="' . $objRow->wrapup . '">' . $objRow->wrapup . '</option>';
				} else  {
					echo '<option value="' . $objRow->wrapup . '" >' . $objRow->wrapup . '</option>';
				}
				$objRow = $resultSet->fetch_object();
			}
		}
	}
	
	
	public function getAllAgentName($selTenantID) {
		if ( $this->mySQL ) {
			$query = "SELECT userid, username FROM users WHERE userdbstatus = 'A' and userlevel = 2 and tenantid=" . $selTenantID . " ORDER BY username ASC;";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
		
 			echo '<option value="0">&nbsp</option>';	
			while ( $objRow ) {
				echo '<option value="' . $objRow->userid . '">' . $objRow->username . '</option>';
				$objRow = $resultSet->fetch_object();
			}
		}
	}
	
	public function getExt($selTenantID) {
		if ( $this->mySQL ) {
			$query = "SELECT CONCAT('SIP/', name ) AS userexten, tenantid FROM sipaccounts WHERE tenantid=" . $selTenantID . " ORDER BY name ASC;";  //$query = "SELECT DISTINCT tenantid, userexten FROM callstatus ;";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $selTenantID == 0 ){
				echo '<option value="0" SELECTED>&nbsp;</option>';
			} else {
				echo '<option value="0">&nbsp;</option>';
			}
			
			while ( $objRow ) {
				if ( $selTenantID == $objRow->tenantid ) {
					echo '<option value="' . $objRow->userexten . '">' . $objRow->userexten . '</option>';
				} /*else  {
					echo '<option value="' . $objRow->userexten . '" >' . $objRow->userexten . '</option>';
				}*/
				$objRow = $resultSet->fetch_object();
			}
		}
	}
	
	public function getCallerID($selTenantID) {
		if ( $this->mySQL ) {
			$query = "SELECT DISTINCT tenantid,callerid FROM callstatus where callerid<>' ' ORDER BY callerid ASC";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $selTenantID == 0 ){
				echo '<option value="0" SELECTED>&nbsp;</option>';
			} else {
				echo '<option value="0">&nbsp;</option>';
			}
			
			while ( $objRow ) {
				if ( $selTenantID == $objRow->tenantid ) {
					echo '<option value="' . $objRow->callerid . '">' . $objRow->callerid . '</option>';
				} /*else  {
					echo '<option value="' . $objRow->callerid . '" >' . $objRow->callerid . '</option>';
				}*/
				$objRow = $resultSet->fetch_object();
			}
		}
	}
	
	public function getLastErrorMessage() { return $this->m_szErrorMessage; }
}

?>
