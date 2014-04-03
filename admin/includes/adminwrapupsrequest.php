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
	require_once('classwrapups.php');
	require_once('classwrapup.php');
	
	
	if ( isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		if ( isset($_GET['adminwrapup']) ) {
			if ( $_GET['adminwrapup'] == 'savewrapup' ) {
				$wrapup = new Wrapup($_GET['wrapupid'], $_GET['tenantid'], $_GET['wrapup'], $_GET['desc']);
				$wrapups =  new Wrapups();
				
				if ( $wrapups->saveWrapup( $wrapup ) ) {
					echo 'adminwrapup:savewrapup:1:'; 
				} else {
					echo 'adminwrapup:savewrapup:0:' . $wrapups->getLastErrorMessage(); 
				}
			} else if ( $_GET['adminwrapup'] == 'wrapuplist' ) {
				$wrapups =  new Wrapups();
				$wrapuplist = $wrapups->displayWrapupList( $_GET['tenantid'] );
				
				$wrapuplist = str_replace(":", "<dquote />", $wrapuplist);
				$wrapuplist = str_replace("|", "<dpipe />", $wrapuplist);
				echo 'adminwrapup:wrapuplist:' . $wrapuplist;
			} else if ( $_GET['adminwrapup'] == 'wrapupinfo' ) {
				$wrapups =  new Wrapups();
				$wrapup = $wrapups->getWrapupInfo($_GET['wrapupid']);
				
				$szWrapupInfo = $wrapup->getWrapupID() . '|' . $wrapup->getTenantID() . '|' . $wrapup->getWrapup() .
					'|' . $wrapup->getDescription();
					
				$szWrapupInfo = str_replace(":", "<dquote />", $szWrapupInfo);
				$szWrapupInfo = str_replace("|", "<dpipe />", $szWrapupInfo);
				
				echo 'adminwrapup:wrapupinfo:' . $szWrapupInfo;
			} else if ( $_GET['adminwrapup'] == 'wrapupdelete' ) {
                                $errorWrapupsDelete = 'Wrapup ID Not Deleted: ';
                                $wrapups = new Wrapups();

                                if ( isset($_GET['wrapups']) ) {
                                        $arrayWrapups = explode( ";", $_GET['wrapups']);
					foreach ( $arrayWrapups as $wrapupid ) {
						if(!$wrapups->deleteWrapup( $wrapupid ))
                                                {
                                                        $errorWrapupsDelete .= $wrapupid . ',';
                                                }
					}

                                        if($errorWrapupsDelete=='Wrapup ID Not Deleted: ')
                                            echo "adminwrapup:wrapupdelete:1:Successfully deleted record(s).";
                                        else
                                            echo "adminwrapup:wrapupdelete:0:Deletion error !!!:" . $errorWrapupsDelete;
                                }
                        }
		}
	}
?>