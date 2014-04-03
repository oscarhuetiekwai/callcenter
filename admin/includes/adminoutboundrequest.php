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
	require_once('classoutbounds.php');
	require_once('classoutbound.php');
	
	if ( isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		if ( isset($_GET['adminoutbound']) || isset($_POST['adminoutbound']) ) {
			
			$szAdminOutbound = "";
			
			if ( isset($_GET['adminoutbound']) ) {
				$szAdminOutbound = $_GET['adminoutbound'];
			} else if ( isset($_POST['adminoutbound']) ) {
				$szAdminOutbound = $_POST['adminoutbound'];
			}
			
			if ( $szAdminOutbound == "saveoutbound" ) {
				$nTenantID = $_GET['tenantid'];
				$nOutboundID = $_GET['outboundid'];
				$nOutboundType = $_GET['outboundtype'];
				$szOutbound = $_GET['outbound'];
				$dtOutboundStart = $_GET['outboundstart'];
				$dtOutboundEnd = $_GET['outboundend'];
				$szSkills = $_GET['skills'];
				$szWrapups = $_GET['wrapups'];
				
				$outbound = new Outbound($nOutboundID,$nTenantID,$szOutbound,$nOutboundType,$dtOutboundStart,$dtOutboundEnd);
				
				if ( $szSkills != '' ) {
					$arrSkills = explode(',',$szSkills);
					
					foreach( $arrSkills as $nSkill ) {
						$outbound->addOutboundSkill( $nSkill );
					}
				}
				
				if ( $szWrapups != '' ) {
					$arrWrapups = explode(',',$szWrapups);
					
					foreach ( $arrWrapups as $nWrapup ) {
						$outbound->addOutboundWrapup( $nWrapup );
					}
				}
				
				for ( $nCnt = 0; $nCnt < 7; $nCnt++ ) {
					if ( isset( $_GET['chkday' . $nCnt] ) ) {
						if ( $_GET['chkday' . $nCnt] == 1 ) {
							$outboundSched = new OutboundSched($nCnt,1,$_GET['timestart' . $nCnt],$_GET['timeend' . $nCnt]);
							
							$outbound->addOutboundSched( $outboundSched );
						}
					}
				}
				
				$outbounds = new Outbounds();
				
				if ( $outbounds->saveOutbound( $outbound ) ) {
					echo 'adminoutbound:saveoutbound:1:';
				} else {
					echo 'adminoutbound:saveoutbound:0:' . $outbounds->getLastErrorMessage();
				}
			
			} else if ( $szAdminOutbound == "outboundinfo" ) {
				$outbounds = new Outbounds();
				$skills = "";
				$wrapups = "";
				$outboundschedules = "";
				
				$outbound = $outbounds->getOutboundInfo( $_GET['outboundid'] );
				
				foreach ( $outbound->getOutboundSkills() as $nSkill ) {
					if ( $skills == '' ) {
						$skills = $nSkill;
					} else {
						$skills .= ( ',' . $nSkill );
					}
				}
				
				foreach ( $outbound->getOutboundWrapups() as $nWrapup ) {
					if ( $wrapups == '' ) {
						$wrapups = $nWrapup;
					} else {
						$wrapups .= ( ',' . $nWrapup );
					}
				}
				
				foreach ( $outbound->getOutboundScheds()  as $outboundsched ) {
					if ( $outboundschedules == '' ) {
						$outboundschedules = ($outboundsched->getDay() . '^' . $outboundsched->getTimeStart() . '^' . $outboundsched->getTimeEnd());
					} else {
						$outboundschedules .= (',' . $outboundsched->getDay() . '^' . $outboundsched->getTimeStart() . '^' . $outboundsched->getTimeEnd());
					}
				}
				
				$outboundschedules = str_replace(":", "<dcolon />", $outboundschedules);
				
				if ( $outbound ) {
					echo 'adminoutbound:outboundinfo:1:' . $outbound->getOutboundID() . '|' . $outbound->getOutbound() . '|' . 
						$outbound->getOutboundType() . '|' . $outbound->getOutboundStart() . '|' . $outbound->getOutboundEnd() .
						'|' . $skills . '|' . $wrapups . '|' . $outboundschedules;
				} else {
					echo 'adminoutbound:outboundinfo:0:';
				}
			} else if ( $szAdminOutbound == "outboundlist") {
				$outbounds = new Outbounds();
				echo 'adminoutbound:outboundlist:' . $outbounds->displayOutboundList(  $_GET['tenantid'], $_GET['filter'] );
			} else {
				echo 'invalid admin outbound parameter[' . $szAdminOutbound . ']!!!';
			}
		} else {
			echo 'invalid admin outbound request!!!';
		}
	}
	
?>