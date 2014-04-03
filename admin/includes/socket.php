<?php

class ClientSocket {
	private $m_szServer = 'localhost';
	private $m_nPort = 8120;
	private $m_Socket;
	
	function __construct($szServerIP, $nPort) {
		$this->m_szServer = $szServerIP;
		$this->m_nPort = $nPort;
	}
	
	function __destruct() {
		if ( isset($this->m_Socket) ) {
			socket_close( $this->m_Socket );
		}
	}
	
	public function sendMessage($szMessage) {
		$szResponse = NULL;
		
		$szMessage .= "\r\n";
		
		if ( isset( $this->m_Socket ) ) {
			// socket_write( $this->m_Socket, $szMessage, strlen( $szMessage ));
			if ( socket_write( $this->m_Socket, $szMessage, strlen( $szMessage )) === FALSE ) {
				$szResponse = NULL;
			} else {
				$szResponse = $this->readSocketForDataLength( 1000 );
			}
		}
		
		return $szResponse;
	}
	
	private function readSocketForDataLength ($len)
	{
    	$offset = 0;
	    $socketData = '';
    
    	while ($offset < $len) {
        	if (($data = socket_read ($this->m_Socket, $len-$offset)) === false) {
            	//$this->error();
	            return false;
    	    }
        
        	$dataLen = strlen ($data);
	        $offset += $dataLen;
    	    $socketData .= $data;
        
        	if ($dataLen == 0) { break; }
	    }

    	return $socketData;
	}
	
	public function connect() {
		$bReturn = FALSE;
		
		set_time_limit( 10 );
		$this->m_Socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		
		if ( isset($this->m_Socket) ) {
			try {
                                socket_set_option($this->m_Socket, SOL_SOCKET, SO_RCVTIMEO, array("sec"=>2, "usec"=>500));
                                socket_set_option($this->m_Socket, SOL_SOCKET, SO_SNDTIMEO, array("sec"=>2, "usec"=>500));
				if ( @socket_connect( $this->m_Socket, $this->m_szServer, $this->m_nPort ) ) $bReturn = TRUE;
			} catch (Exception $e ) {
			}
		}
		
		return $bReturn;
	}
}
?>
