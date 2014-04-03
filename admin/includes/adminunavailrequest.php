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
	require_once('classunavailcode.php');
	require_once('classunavailcodes.php');
	
	
	if ( isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		if ( isset($_GET['adminunavail']) ) {
			if ( $_GET['adminunavail'] == 'unavailsave' ) {
				$unavail = new UnavailCode($_GET['userstatusid'], $_GET['tenantid'], $_GET['userstatus'], $_GET['productive']);
				$unavails = new UnavailCodes();
				
				if ( $unavails->saveUnavail( $unavail ) ) {
					echo 'adminunavail:unavailsave:1:'; 
				} else {
					echo 'adminunavail:unavailsave:0:' . $skills->getLastErrorMessage(); 
				}
			} else if ( $_GET['adminunavail'] == 'unavaillist' ) {
				$unavails = new UnavailCodes();
				$unavaillist = $unavails->displayUnavailableCodes( $_GET['tenantid'], $_GET['filter'] );
				
				$unavaillist = str_replace(":", "<dquote />", $unavaillist);
				$unavaillist = str_replace("|", "<dpipe />", $unavaillist);
				echo 'adminunavail:unavaillist:' . $unavaillist;
			} else if ( $_GET['adminunavail'] == 'unavailinfo' ) {
				$unavails = new UnavailCodes();
				$unavail = $unavails->getUnavailInfo( $_GET['userstatusid'] );
				
				$unavailInfo = $unavail->getUserStatusID() . '|' . $unavail->getTenantID() . '|' . $unavail->getUserStatus() . 
					'|'. $unavail->getProductive();
					
				$unavailInfo = str_replace(":", "<dquote />", $unavailInfo);
				$unavailInfo = str_replace("|", "<dpipe />", $unavailInfo);
				
				echo 'adminunavail:unavailinfo:' . $unavailInfo;
			} else if ( $_GET['adminunavail'] == 'unavaildelete' ) {
                                $errorUnavailsDelete = 'Unavailable ID Not Deleted: ';
                                $unavailablecodes = new UnavailCodes();

                                if ( isset($_GET['unavails']) ) {
                                        $arrayUnavails = explode( ";", $_GET['unavails']);
					foreach ( $arrayUnavails as $unavailid ) {
						if(!$unavailablecodes->deleteUnavail( $unavailid ))
                                                {
                                                        $errorUnavailsDelete .= $unavailid . ',';
                                                }
					}

                                        if($errorUnavailsDelete=='Unavailable ID Not Deleted: ')
                                            echo "adminunavail:unavaildelete:1:Successfully deleted record(s).";
                                        else
                                            echo "adminunavail:unavaildelete:0:Deletion error !!!:" . $errorUnavailsDelete;
                                }
                        }
		}
	}
?>