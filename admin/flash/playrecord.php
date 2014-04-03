<?php

if ( isset($_GET['filename'] ) ) {
	$recordingPath = '/var/spool/asterisk/monitor/';
	
	if ( is_file( $recordingPath . $_GET['filename'] . '.mp3' ) ) {
		$_GET['filename'] = $_GET['filename'] . '.mp3';
		//echo 'located' . $_GET['filename'] . '.mp3';
	} else if ( is_file( $recordingPath . $_GET['filename'] . '.wav' ) ) {
		$_GET['filename'] = $_GET['filename'] . '.wav';
		//echo 'located' . $_GET['filename'] . '.wav';
	} else if ( is_file( $recordingPath . $_GET['filename'] . '.WAV' ) ) {
		$_GET['filename'] = $_GET['filename'] . '.WAV';
		//echo 'located' . $_GET['filename'] . '.WAV';
	} else if ( is_file( $recordingPath . $_GET['filename'] . '.gsm' ) ) {
		$_GET['filename'] = $_GET['filename'] . '.gsm';
		//echo 'located' . $_GET['filename'] . '.gsm';
	}
	
	if ( is_file( $recordingPath . $_GET['filename'] ) ) {
		
		$extension = strtolower(substr(strrchr($_GET['filename'],"."),1));
		$size = filesize($recordingPath . $_GET['filename']);

		$ctype ='';
		switch( $extension ) {
			case "mp3": $ctype="audio/mpeg"; break;
			case "wav": $ctype="audio/x-wav"; break;
			case "Wav": $ctype="audio/x-wav"; break;
			case "WAV": $ctype="audio/x-wav"; break;
			case "gsm": $ctype="audio/x-gsm"; break;

   			 // not downloadable
   			 default: die("<b>404 File not found!</b>"); break ;
  		}
		
		$fp = fopen($recordingPath . $_GET['filename'], "rb" );
		
		if ( $fp ) {
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: public");
			header("Content-Description: wav file");
			header("Content-Type: " . $ctype);
			header("Content-Disposition: attachment; filename=" . $_GET['filename']);
			header("Content-Transfer-Encoding: binary");
			header("Content-length: " . $size);
			fpassthru($fp);
		}
		
		
	}
} else {
}
?>