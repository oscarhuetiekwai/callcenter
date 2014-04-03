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
	require_once('classusers.php');
	require_once('classuser.php');
	require_once('socket.php');
	require_once('serverresponse.php');
	
	if ( isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		if ( isset($_GET['adminuser']) ) {
			if ( $_GET['adminuser'] == 'userlist' ) {
				$users = new Users();
				
				$displayUserList = $users->displayUserList($_GET['tenantid'], $_GET['filter']);
				$displayUserList = str_replace(":", "<dquote />", $displayUserList);
				echo 'adminuser:userlist:' . $displayUserList ;
			} else if ( $_GET['adminuser'] == 'userqueuesreload' ) {
				$socket = new ClientSocket(SERVER_IP,SERVER_PORT);
		
				if ( $socket->connect() ) {
					$serverResponse = new ServerResponse($socket->sendMessage('<userqueuesreload><session>' . $_SESSION['WEB_ADMIN_SESSION'] .
						'</session><userid>' . $_GET['userid'] . '</userid></userqueuesreload>'));
					
					echo 'adminuser:userqueuesreload:' . $serverResponse->getResponseObject();
					
				} else {
					throw new Exception("Unable to connect to the server!!!!");
				}
			} else if ( $_GET['adminuser'] == 'userinfo' ) {
				$users = new Users();
				
				$user = $users->getUserInfo( $_GET['userid'] );
				
				$skills = '';
				foreach( $user->getSkills() as $skillSet ) {
					if ( $skills == '' ) 
						$skills = $skillSet;
					else
						$skills .= ('|' . $skillSet);
				}
				
				$queues = '';
				foreach( $user->getQueues() as $queue ) {
					if ( $queues == '' )
						$queues = $queue;
					else
						$queues .= ('|' . $queue);
				}
				
				$wrapups = '';
				foreach( $user->getWrapups() as $wrapup ) {
					if ( $wrapups == '' )
						$wrapups = $wrapup;
					else
						$wrapups .= ( '|' . $wrapup );
				}
				
				$skills = str_replace(":", "<dquote />", $skills);
				$skills = str_replace("|", "<dpipe />", $skills);
				$queues = str_replace("|", "<dpipe />", $queues);
				$wrapups = str_replace("|", "<dpipe />", $wrapups );
				
				$userinfo = $user->getUserID() . '|' . $user->getTenantID() . '|' . $user->getTenantName() .
					'|' . $user->getUsername() . '|' . $user->getUserpass() . '|' . $user->getUserLevel() .
					'|' . $user->getLastname() . '|' . $user->getFirstname() . '|' . $user->getSupervisor() .
					'|' . $skills . '|' . $queues . '|' . $user->getPQueueTimeout() . '|' . 
					$user->getQueueRouteType() . '|' . $user->getQueueRouteValue()  . '|' . $wrapups;
				
				echo 'adminuser:userinfo:' . $userinfo;
			} else if ( $_GET['adminuser'] == 'saveuser' ) {
				$users = new Users();
				$user = new User($_GET['agentid'],$_GET['tenantid'],'',$_GET['uid'],$_GET['pwd'],$_GET['ulevel'],
								$_GET['lastname'], $_GET['firstname'] );
				
				if ( $_GET['skills'] != '' ) {
					$arraySkills = explode( "|", $_GET['skills']);
					foreach ($arraySkills as $skillset) {
						$arrSkillSet = explode(":", $skillset);
						$user->addSkills($arrSkillSet[0], $arrSkillSet[1]);
					}	
				}
	
				if ( $_GET['queues'] != '' ) {
					$arrayQueues = explode( "|", $_GET['queues']);
					foreach ($arrayQueues as $queue ) {
						$user->addQueue( $queue );
					}
				}
				
				if ( $_GET['wrapups'] != '' ) {
					$arrayWrapups = explode( "|", $_GET['wrapups']);
					foreach ( $arrayWrapups as $wrapup ) {
						$user->addWrapup( $wrapup );
					}
				}
				
				
				$user->setPQueueTimeout( $_GET['pqtimeout'] );
				$user->setQueueRouteType( $_GET['queueroutetype'] );
				$user->setQueueRouteValue( $_GET['queueroutevalue'] );
				$user->setSupervisor( $_GET['supervisor'] );
				
				if ( $users->saveUser( $user )) {
					echo 'adminuser:saveuser:1';
				} else {
					echo 'adminuser:saveuser:0:' . $users->getLastErrorMessage(); 
				}
			} else if ( $_GET['adminuser'] == 'userdelete' ) {
                                $errorUsersDelete = 'User ID Not Deleted: ';
                                $users = new Users();
                                
                                if ( isset($_GET['users']) ) {
                                        $arrayUsers = explode( ";", $_GET['users']);
					foreach ( $arrayUsers as $userid ) {
						if(!$users->deleteUser( $userid ))
                                                {
                                                        $errorUsersDelete .= $userid . ',';
                                                }
					}
                                        
                                        if($errorUsersDelete=='User ID Not Deleted: ')
                                            echo "adminuser:deleteuser:1:Successfully deleted record(s).";
                                        else
                                            echo "adminuser:deleteuser:0:Deletion error !!!!:" . $errorUsersDelete;
                                }
                        }
		} 
	} 
?>