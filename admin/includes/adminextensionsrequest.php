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
	require_once('classextensions.php');
	require_once('classextension.php');
	
	//(name, tenantid, secret, defaultuser, context, callerid)
	if ( isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		if ( isset($_GET['adminextension']) ) {
			if ( $_GET['adminextension'] == 'saveextension' ) {
				$extension = new Extension($_GET['extensionid'], $_GET['tenantid'], $_GET['tenantname'], $_GET['extensionname'], $_GET['callerid'], $_GET['secret']);
				
				$extensions =  new Extensions();
				
				if ( $extensions->saveExtension( $extension ) ) {
					echo 'adminextension:saveextension:1:'; 
				} else {
					echo 'adminextension:saveextension:0:' . $extensions->getLastErrorMessage(); 
				}
			} else if ( $_GET['adminextension'] == 'extensionlist' ) {
				$extensions =  new Extensions();
				$extensionlist = $extensions->displayExtensionsList( $_GET['tenantid'] );
				
				$extensionlist = str_replace(":", "<dquote />", $extensionlist);
				$extensionlist = str_replace("|", "<dpipe />", $extensionlist);
				echo 'adminextension:extensionlist:' . $extensionlist;
			} else if ( $_GET['adminextension'] == 'extensioninfo' ) {
				$extensions =  new Extensions();
				$extension = $extensions->getExtensionInfo($_GET['extensionid']);
				
				$szExtensionInfo = $extension->getExtensionID() . '|' . $extension->getTenantID() . '|' . $extension->getTenantName() .
					'|' . $extension->getExtensionName() . '|' . $extension->getCallerId() . '|' . $extension->getSecret();
					
				$szExtensionInfo = str_replace(":", "<dquote />", $szExtensionInfo);
				$szExtensionInfo = str_replace("|", "<dpipe />", $szExtensionInfo);
				
				echo 'adminextension:extensioninfo:' . $szExtensionInfo;
			}
		}
	}
?>