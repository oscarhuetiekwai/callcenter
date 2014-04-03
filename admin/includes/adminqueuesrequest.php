<?php
//no  cache headers 
header("Expires: Mon, 26 Jul 2012 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<?php
    session_start();
	require_once('config.php'); 
	require_once('errhandler.php');
	require_once('classqueues.php');
	require_once('classqueue.php');
	require_once('socket.php');
	require_once('serverresponse.php');
	
	if ( isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		if ( isset($_GET['adminqueue']) ) {
			if ( $_GET['adminqueue'] == 'savequeue' ) {
				$queue = new Queue($_GET['queueid'], $_GET['tenantid'], '', $_GET['queue'], $_GET['timeout'], 
					$_GET['servicelevel'], $_GET['priority'], $_GET['wrapuptime']);
				$queues =  new Queues();
				
				if ( $_GET['skills'] != '' ) {
					$arrSkills = explode( ',', $_GET['skills'] );
					
					foreach ( $arrSkills as $nSkill ) {
						$queue->addSkill( $nSkill );
					}
				}
				
				if ( $_GET['wrapups'] ) {
					$arrWrapups = explode( ',', $_GET['wrapups'] );
					
					foreach ( $arrWrapups as $nWrapup ) {
						$queue->addWrapup( $nWrapup );
					}
				}
				
				if ( $queues->saveQueue( $queue ) ) {
					echo 'adminqueue:savequeue:1:'; 
				} else {
					echo 'adminqueue:savequeue:0:' . $queues->getLastErrorMessage(); 
				}
			} else if ( $_GET['adminqueue'] == 'queuelist' ) {
				$queues =  new Queues();
				$queuelist = $queues->displayQueueList( $_GET['tenantid'] );
				
				$queuelist = str_replace(":", "<dquote />", $queuelist);
				$queuelist = str_replace("|", "<dpipe />", $queuelist);
				echo 'adminqueue:queuelist:' . $queuelist;
			} else if ( $_GET['adminqueue'] == 'queueinfo' ) {
				$queues =  new Queues();
				$queue = $queues->getQueueInfo($_GET['queueid']);
				
				$queueSkill = '';
				
				foreach ( $queue->getSkills() as $skill ) {
					if ( $queueSkill == '' )
						$queueSkill = $skill;
					else
						$queueSkill .= (':' . $skill);
				}
				
				$queueWrapup = '';
				
				foreach ( $queue->getWrapups() as $wrapup ) {
					if ( $queueWrapup == '' )
						$queueWrapup = $wrapup;
					else
						$queueWrapup .= ( ':' . $wrapup );
				}
				
				$szQueueInfo = $queue->getQueueID() . '|' . $queue->getTenantID() . '|' . $queue->getQueueName() .
					'|' . $queue->getTimeout() . '|' . $queue->getServiceLevel() . '|' . $queue->getPriority() . 
					'|' . $queue->getWrapupTime() . '|' . $queueSkill . '|' . $queueWrapup;
					
				$szQueueInfo = str_replace(":", "<dquote />", $szQueueInfo);
				$szQueueInfo = str_replace("|", "<dpipe />", $szQueueInfo);
				
				echo 'adminqueue:queueinfo:' . $szQueueInfo;
			} else if ( $_GET['adminqueue'] == 'inboundqueuesreload' ) {
				$socket = new ClientSocket(SERVER_IP,SERVER_PORT);
		
				if ( $socket->connect() ) {
					$serverResponse = new ServerResponse($socket->sendMessage('<inboundqueuesreload><session>' . $_SESSION['WEB_AGENT_SESSION'] .
						'</session></inboundqueuesreload>'));
					
					echo 'adminqueue:inboundqueuesreload:' . $serverResponse->getResponseObject();
					
				} else {
					throw new Exception("Unable to connect to the server!!!!");
				}
			} else if ( $_GET['adminqueue'] == 'queuedelete' ) {
                                $errorQueuesDelete = 'Queue ID Not Deleted: ';
                                $queues = new Queues();

                                if ( isset($_GET['queues']) ) {
                                        $arrayQueues = explode( ";", $_GET['queues']);
					foreach ( $arrayQueues as $queueid ) {
						if(!$queues->deleteQueue( $queueid ))
                                                {
                                                        $errorQueuesDelete .= $queueid . ',';
                                                }
					}

                                        if($errorQueuesDelete=='Queue ID Not Deleted: ')
                                            echo "adminqueue:queuedelete:1:Successfully deleted record(s).";
                                        else
                                            echo "adminqueue:queuedelete:0:Deletion error !!!:" . $errorQueuesDelete;
                                }
                        }
		}
	}
?>