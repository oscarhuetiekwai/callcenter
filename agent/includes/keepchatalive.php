<?php
//tail -f accordia.log | grep -i chat      #check CIS log
	session_start();
	require_once('config.php');
	require_once('errhandler.php');
	require_once('socket.php');
	require_once('serverresponse.php');

	if ( isset($_SESSION['WEB_AGENT_SESSION']) ) {
		$socket = new ClientSocket(SERVER_IP,SERVER_PORT);
                if ( isset($_GET['chataction']) ) {
                    
                    $chataction = $_GET['chataction'];

                    if ( $chataction == "getchatcontactlist" )
                    {
                        if ( $socket->connect() ) {
                                //$serverResponse = new ServerResponse($socket->sendMessage('<getchatcontactlist><sessionid>' . $_SESSION['WEB_ADMIN_SESSION'] . '</sessionid></getchatcontactlist>'));

                                echo $socket->sendMessage('<getchatcontactlist><sessionid>' . $_SESSION['WEB_AGENT_SESSION'] . '</sessionid></getchatcontactlist>') . ":" . $_SESSION['WEB_AGENT_USER'];

                        } else {
                                throw new Exception("Unable to connect to the server!!!!");
                        }
                    } else if ( $chataction == "sendchat" ) {
                        if ( isset($_GET['toUserId']) && isset($_GET['message']) ) {
                            if ( $socket->connect() ) {
                                    $serverResponse = new ServerResponse($socket->sendMessage('<sendchat><sessionid>' . $_SESSION['WEB_AGENT_SESSION'] . '</sessionid><userto>' . $_GET['toUserId'] . '</userto><msg> ' . $_GET['message'] . ' </msg></sendchat>'));

                                    echo $serverResponse->getResponseObject();

                            } else {
                                    throw new Exception("Unable to connect to the server!!!!");
                            }
                        }
                    } else if ( $chataction == "broadcastchat" ) {
                        if ( $socket->connect() ) {
                                $serverResponse = new ServerResponse($socket->sendMessage('<broadcastchat><sessionid>sessionid</sessionid><msg>message</msg><userto1>us erid1</userto1>...<userton>useridn</userton></broadcastchat>'));

                                echo $serverResponse->getResponseObject();

                        } else {
                                throw new Exception("Unable to connect to the server!!!!");
                        }
                    } else if ( $chataction == "chatcontactstatus" ) {
                        if ( $socket->connect() ) {
                                $serverResponse = new ServerResponse($socket->sendMessage('<chatcontactstatus></chatcontactstatus>'));

                                echo $serverResponse->getResponseObject();

                        } else {
                                throw new Exception("Unable to connect to the server!!!!");
                        }
                    } else {
                        echo 'keepalive:0:';
                    }
                }
	} else {
		echo 'keepalive:0:';
	}
?>