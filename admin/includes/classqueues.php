<?php

require_once('config.php'); 
require_once('errhandler.php');
require_once('classqueue.php');

class Queues {
	private $mySQL;
	private $m_szErrorMessage = '';
	
	function __construct() {
		$this->mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
	}
	
	function __destruct() {
		$this->mySQL->close();
	}
	
	public function getQueuesCount($nTenantID) {
		$nTenantsCount = 0;
		
		if ( $this->mySQL ) {
			$query = "SELECT count(*) AS totalcount FROM tenantqueues";
			
			if ($nTenantID != 0 ) {
				$query = "SELECT count(*) AS totalcount FROM tenantqueues WHERE queuedbstatus = 'A' and tenantid=" . $nTenantID;
			}
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$nTenantsCount = $objRow->totalcount;
			}
		}
		
		return $nTenantsCount;
	}
	
	public function getQueues($nTenantID) {
		$arrQueues = array();
		
		if ( $this->mySQL ) {
			$query = "SELECT tenantqueueid, tq.tenantid AS tenantid, t.name AS tenantname, queuename, timeout, " .
				"servicelevel, weight, wrapuptime FROM tenantqueues tq INNER JOIN queues q ON " .
				"q.name = tq.queuenameinternal LEFT JOIN tenants t ON t.tenantid = " .
				"tq.tenantid WHERE tq.queuedbstatus='A' ORDER BY tq.queuename ASC";
				
			if ( $nTenantID != 0 ) {
				$query = "SELECT tenantqueueid, tq.tenantid AS tenantid, t.name AS tenantname, queuename, timeout, " .
					"servicelevel, weight, wrapuptime FROM tenantqueues tq INNER JOIN queues q ON " .
					"q.name = tq.queuenameinternal LEFT JOIN tenants t ON t.tenantid = " .
					"tq.tenantid WHERE tq.queuedbstatus='A' AND tq.tenantid=" . $nTenantID . " ORDER BY tq.queuename ASC";
			}
			
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			while ( $objRow ) {
				$queue = new Queue($objRow->tenantqueueid,$objRow->tenantid,$objRow->tenantname,
								   $objRow->queuename,$objRow->timeout,$objRow->servicelevel,
								   $objRow->weight,$objRow->wrapuptime);
				
				array_push($arrQueues, $queue);
				$objRow = $resultSet->fetch_object();
			}
		}
		
		return $arrQueues;
	}
	
	public function getQueueInfo($nQueueID) {
		$queue = NULL;
		
		if ( $this->mySQL ) {
			$query = "SELECT tenantqueueid, tq.tenantid AS tenantid, t.name AS tenantname, queuename, timeout, " .
				"servicelevel, weight, wrapuptime FROM tenantqueues tq INNER JOIN queues q ON " .
				"q.name = tq.queuenameinternal LEFT JOIN tenants t ON t.tenantid = " .
				"tq.tenantid WHERE tq.tenantqueueid=" . $nQueueID . " ORDER BY tq.queuename ASC;";
			
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$queue = new Queue($objRow->tenantqueueid,$objRow->tenantid,$objRow->tenantname,
								   $objRow->queuename,$objRow->timeout,$objRow->servicelevel,
								   $objRow->weight,$objRow->wrapuptime);
			}
			
			$query = "SELECT * FROM queueskills WHERE tenantqueueid=" . $nQueueID;
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			while ( $objRow ) {
				if ( $queue ) {
					$queue->addSkill($objRow->skillid);
				}
				$objRow = $resultSet->fetch_object();
			}
			
			$query = "SELECT * FROM queuewrapups WHERE tenantqueueid=" . $nQueueID;
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			while ( $objRow ) {
				if ( $queue ) {
					$queue->addWrapup($objRow->wrapupid);
				}
				$objRow = $resultSet->fetch_object();
			}
		}
		
		return $queue;
	}
	
	public function getAllTenantsOption($selTenantID) {
		if ( $this->mySQL ) {
			$query = "SELECT tenantid, name FROM tenants ORDER BY name ASC;";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			
			if ( $selTenantID == 0 ){
				echo '<option value="0" SELECTED>SYSTEM</option>';
			} else {
				echo '<option value="0">SYSTEM</option>';
			}
			
			while ( $objRow ) {
				if ( $selTenantID == $objRow->tenantid ) {
					echo '<option value="' . $objRow->tenantid . '" SELECTED>' . $objRow->name . '</option>';
				} else  {
					echo '<option value="' . $objRow->tenantid . '" >' . $objRow->name . '</option>';
				}
				$objRow = $resultSet->fetch_object();
			}
		}
	}
	
	public function getAllTenantSkillsOption($selTenantID, $arrSkills) {
		if ( $this->mySQL ) {
			$query = "SELECT skillid, skill FROM skills WHERE tenantid=" . $selTenantID . " ORDER BY skill ASC;";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();

			$nCnt = 1;
			echo '<ul width="200" style="width: 200px">';
			while ( $objRow ) {
//				echo '<option value="' . $objRow->skillid . '">' . $objRow->skill . '</option>';

//				echo '<li><label for="chk' . $nCnt . '"><input type="checkbox" name="chk' . $nCnt . '" id="chk' . $nCnt . '" value="' . 
//					$objRow->skillid . '"/>' . $objRow->skill . '<input type="text" name="txtSkill' . $objRow->skillid . '" id="txtSkill' .
//					$objRow->skillid . '" width="100" style="width: 100px"/></label></li>';

				echo '<li><label for="chk' . $nCnt . '"><input type="checkbox" name="chk' . $nCnt . '" id="chk' . $nCnt . '" value="' . 
					$objRow->skillid . '"/>' . $objRow->skill . '</label></li>';

				$nCnt++;
				$objRow = $resultSet->fetch_object();
			}
			echo '</ul>';			
		}
	}
	
	public function getAllTenantSkillsCount($selTenantID) {
		$nSkillsCount = 0;
		
		if ( $this->mySQL ) {
			$query = "SELECT count(*) as totalskills FROM skills WHERE tenantid=" . $selTenantID ;
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();

			if ( $objRow ) {
				$nSkillsCount = $objRow->totalskills;
			}
		}
		
		return $nSkillsCount;
	}
	
	
	public function saveQueue($queue) {
		$bReturn = false;
		
		if ( !$this->mySQL ) {
			$this->m_szErrorMessage = 'Error connecting to the database!!!';
			return $bReturn;
		}
		
		if ( $queue->getQueueID() > 0 ) {
			// Update queue information
			$query = "SELECT * FROM tenantqueues WHERE tenantqueueid=" . $queue->getQueueID();
			$resultSet = $this->mySQL->query( $query );
			$objRow = $resultSet->fetch_object();
			
			if ( $objRow ) {
				$szQueueInternal = $objRow->queuenameinternal;
				$query = "UPDATE queues SET timeout=" . $queue->getTimeout() . ", wrapuptime=" . $queue->getWrapupTime() .
					", servicelevel=" . $queue->getServiceLevel() . ", weight=" . $queue->getPriority() . " WHERE name='" .
					$szQueueInternal . "';";
					
				$this->mySQL->query( $query );
				
				$query ="UPDATE tenantqueues SET queuename='" . $queue->getQueueName() . "' WHERE tenantqueueid=" .
					$queue->getQueueID();
					
				$this->mySQL->query( $query );
				
				$query ="DELETE FROM queueskills WHERE tenantqueueid=" . $queue->getQueueID();
				$this->mySQL->query( $query );
				
				foreach ( $queue->getSkills() as $nSkill ) {
					$query = "INSERT INTO queueskills (tenantqueueid, skillid) VALUES (" . 
						$queue->getQueueID() . ", " . $nSkill . ");";
					$this->mySQL->query( $query );									   
				}
				
				$query = "DELETE FROM queuewrapups WHERE tenantqueueid=" . $queue->getQueueID(); 
				$this->mySQL->query( $query );
				
				foreach ( $queue->getWrapups() as $nWrapup ) {
					$query = "INSERT INTO queuewrapups (tenantqueueid, wrapupid) VALUES (" .
						$queue->getQueueID() . ", " . $nWrapup . ");";
					$this->mySQL->query( $query );
				}
					
				$bReturn = true;
			}
		} else {
			// Add new queue information
			$szQueueName = $queue->getQueueName();
			$szQueueNameInternal = 't' . $queue->getTenantID() . '-' . $queue->getQueueName();
			
			$sqlTenantQueuesCommand = "INSERT INTO tenantqueues(tenantid,queuename,queuenameinternal) VALUES (" . 
				$queue->getTenantID() . ", '" .  $szQueueName . "', '" . $szQueueNameInternal . "');";
			$sqlTenantQueuesQuery = "SELECT tenantqueueid FROM tenantqueues WHERE queuenameinternal='" . 
				$szQueueNameInternal . "';";
			$sqlQueueCommand = "INSERT INTO queues(name,timeout,wrapuptime,servicelevel,weight) VALUES ('" . 
				$szQueueNameInternal . "', " . $queue->getTimeout() . ", " . $queue->getWrapupTime() . ", " . 
				$queue->getServiceLevel() . ", " . $queue->getPriority() . ");";
			
			$this->mySQL->query( $sqlTenantQueuesCommand  );
			$resultSet = $this->mySQL->query( $sqlTenantQueuesQuery  );
			
			$objRow = $resultSet->fetch_object();
			if ( $objRow ) {
				$nTenantQueueID = $objRow->tenantqueueid;
				
				if ( $nTenantQueueID > 0 ) {
					$this->mySQL->query( $sqlQueueCommand  );
					$temparraySkills = $queue->getSkills();
					
					foreach ( $temparraySkills as $tempSkillID) {
						$szQueueSkillsCommand = "INSERT INTO queueskills (tenantqueueid, skillid) VALUES (" .
							$nTenantQueueID . ", " . $tempSkillID . ");";
						
						$this->mySQL->query( $szQueueSkillsCommand  );
					}
					
					foreach ( $queue->getWrapups() as $nWrapup ) {
						$query = "INSERT INTO queuewrapups (tenantqueueid, wrapupid) VALUES (" .
							$nTenantQueueID . ", " . $nWrapup . ");";
						$this->mySQL->query( $query );
					}
					
					$bReturn = true;
				}
			}
			
		}
		
		return $bReturn;
	}
	
	public function getSkillsList($nTenantID) {
		$szReturn = '<table border="0" cellpadding="0" cellspacing="0">';
	
		if ( $this->mySQL ) {
			$query = "SELECT skillid, skill FROM skills WHERE skilldbstatus='A' AND tenantid=" .
				$nTenantID . " ORDER BY skill ASC";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			$nCnt = 1;
			
			while ( $objRow ) {
				$szChkName = 'chkskill' . $nCnt;
				$szRow = '<tr>';
				$szRow .= '<td width="30px"><input id="' . $szChkName . '" name="' . $szChkName . '" type="checkbox" value="' . $objRow->skillid . 
					'"></td>';
				$szRow .= '<td width="200px">' . $objRow->skill . '</td>';
				$szRow .= '</tr>';
				
				$szReturn .= $szRow;
				$objRow = $resultSet->fetch_object();
				$nCnt++;
			}
		}
		
		$szReturn .= '</table>';
		
		return $szReturn;
	}
	
	public function getWrapupsList($nTenantID) {
		$szReturn = '<table border="0" cellpadding="0" cellspacing="0">';
	
		if ( $this->mySQL ) {
			$query = "SELECT wrapupid, wrapup FROM wrapups WHERE wrapupdbstatus='A' AND tenantid=" .
				$nTenantID . " ORDER BY wrapup ASC";
			
			$resultSet = $this->mySQL->query($query);
			
			$objRow = $resultSet->fetch_object();
			$nCnt = 1;
			
			while ( $objRow ) {
				$szChkName = 'chkwrapup' . $nCnt;
				$szRow = '<tr>';
				$szRow .= '<td width="30px"><input id="' . $szChkName . '" name="' . $szChkName . '" type="checkbox" value="' . $objRow->wrapupid . 
					'"></td>';
				$szRow .= '<td width="200px">' . $objRow->wrapup . '</td>';
				$szRow .= '</tr>';
				
				$szReturn .= $szRow;
				$objRow = $resultSet->fetch_object();
				$nCnt++;
			}
		}
		
		$szReturn .= '</table>';
		
		return $szReturn;
	}
	
	public function displayQueueList($nTenantID) {
		$szReturn = '';
		$nCnt = 1;
		
		foreach ( $this->getQueues($nTenantID) as $queue) {
			$szReturn = $szReturn . '<label for="chkqueue' . 
						$nCnt . '" onclick="onQueueClick(' . $queue->getQueueID() . ');"><input type="checkbox" id="chkqueue"' . $nCnt . 
						'" name="chkqueue" value="' . $queue->getQueueID() . '" /><b><font color="#009999">' . $queue->getQueueName().
						'</font></label>';
			$nCnt++;
		}
		
		return $szReturn;
	}

        public function deleteQueue($queueID) {
                if ( $this->mySQL ) {
                        $query = "UPDATE tenantqueues SET queuedbstatus='D' WHERE tenantqueueid=" . $queueID . ";";
                        $resultSet = $this->mySQL->query( $query );

                        return $resultSet;
                }
        }
	
	public function getLastErrorMessage() { return $this->m_szErrorMessage; }
	
}
?>