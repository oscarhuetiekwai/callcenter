<?php
	session_start();
	require_once('config.php'); 
	require_once('errhandler.php');
	require_once('classagentutil.php');
	
	if ( isset($_SESSION['WEB_AGENT_SESSION']) ) {
//		if ( isset( $_GET['soapclient'] ) ) {
//			if ( $_GET['soapclient'] == 'customercallstart' ) {
//				$nReturn = 0;
//				$soapClient = new SoapClient("http://192.168.0.13/helpdeskws/wscap.asmx?wsdl"); 
//				
//				// Prepare SoapHeader parameters 
//				
//				
//				//$headers = new SoapHeader('http://192.168.0.13/helpdeskws/wscap.asmx', NULL, NULL); 
//			
//				// Prepare Soap Client 
//				//$soapClient->__setSoapHeaders(array($headers)); 
//			
//				// Setup the RemoteFunction parameters 
//				$ap_param = array( 
//							'CallID'    =>    $_GET['callerid'], 
//							'UniqueID'    =>  $_GET['uniqueid'],
//							'IPaddress' => $_SERVER['REMOTE_ADDR'],
//							'MembershipID' => '',
//							'TicketID' => '');
//				
//				// Call RemoteFunction () 
//				$error = 0; 
//				try { 
//					$info = $soapClient->__call("CustomerCallStart", array($ap_param)); 
//				} catch (SoapFault $fault) { 
//				
//					$error = 1; 
//				}
//
//				if ($error == 0) {        
//					//$auth_num = $info->RemoteFunctionResult; 
//					
//					//if ($auth_num < 0) {  
//						unset($soapClient); 
//					//}
//					
//					$nReturn = 1;
//					
//				}
//				
//				echo 'customercallstart:' . $nReturn . ':';
//			} else if ( $_GET['soapclient'] == 'customercallend' ) {
//				$nReturn = 0;
//				$soapClient = new SoapClient("http://192.168.0.13/helpdeskws/wscap.asmx?wsdl"); 
//				
//				// Prepare SoapHeader parameters 
//				
//				
//				//$headers = new SoapHeader('http://192.168.0.13/helpdeskws/wscap.asm'); 
//			
//				// Prepare Soap Client 
//				//$soapClient->__setSoapHeaders(array($headers)); 
//			
//				// Setup the RemoteFunction parameters 
//				$ap_param = array( 
//							'CallID'    =>    $_GET['callerid'], 
//							'UniqueID'    =>  $_GET['uniqueid'],
//							'IPaddress' => $_SERVER['REMOTE_ADDR'],
//							'VoiceURL' => $_GET['voiceurl'],
//							'Duration' => $_GET['duration']);
//				
//				// Call RemoteFunction () 
//				$error = 0; 
//				try { 
//					$info = $soapClient->__call("CustomerCallEnded", array($ap_param)); 
//				} catch (SoapFault $fault) { 			
//					$error = 1; 
//				}
//
//				if ($error == 0) {        
//					unset($soapClient); 
//					
//					$nReturn = 1;
//				}
//				
//				echo 'customercallend:' . $nReturn . ':';
//			} else if ( $_GET['soapclient'] == 'lawyercallstart' ) {
//			} else if ( $_GET['soapclient'] == 'lawyercallend' ) {
//			}
//		} 
//	} else {
//		
//		// Error sending to soap client
//		//echo 'keepalive:0:';
	}

	
?>
