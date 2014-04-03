<?php

set_error_handler('error_handler', E_ALL);

function error_handler( $errNo, $errStr, $errFile, $errLine) {
	if ( ob_get_length() ) ob_clean();
	
	echo '<div id="errhandler"><table><tr><td>Error No.</td><td>' . $errNo . '</td></tr><tr><td>Description</td><td>' .
		$errStr . '</td></tr><tr><td>File</td><td>' . $errFile . '</td></tr><tr><td>Line No.</td><td>' . $errLine . 
		'</td></tr></table></div>';
}

?>