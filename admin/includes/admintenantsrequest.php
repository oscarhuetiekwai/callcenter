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
	require_once('classtenants.php');
	require_once('classtenant.php');
	
	
	if ( isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		if ( isset($_GET['admintenant']) ) {
			if ( $_GET['admintenant'] == 'tenantsave' ) {
				/*$wrapup = new Wrapup($_GET['wrapupid'], $_GET['tenantid'], $_GET['wrapup'], $_GET['desc']);
				$wrapups =  new Wrapups();
				
				if ( $wrapups->saveWrapup( $wrapup ) ) {
					echo 'admintenant:savewrapup:1:'; 
				} else {
					echo 'admintenant:savewrapup:0:' . $wrapups->getLastErrorMessage(); 
				}*/
				$tenant = new Tenant($_GET["tenantid"],$_GET["tenant"],$_GET["contactperson"],$_GET["officeno"],
									$_GET["homeno"],$_GET["mobileno"]);
				$tenants = new Tenants();
				
				if ( $tenants->saveTenant( $tenant ) ) {
					echo 'admintenant:tenantsave:1:';
				} else {
					echo 'admintenant:tenantsave:0:' . $tenants->getLastErrorMessage();
				}
			} else if ( $_GET['admintenant'] == 'tenantlist' ) {
				$tenants =  new Tenants();
				$tenantlist = $tenants->displayTenantsList();
				
				$tenantlist = str_replace(":", "<dquote />", $tenantlist);
				$tenantlist = str_replace("|", "<dpipe />", $tenantlist);
				echo 'admintenant:tenantlist:' . $tenantlist;
			} else if ( $_GET['admintenant'] == 'tenantinfo' ) {
				$tenants =  new Tenants();
				$tenant = $tenants->getTenantInfo($_GET['tenantid']);
				
				$szTenantInfo = $tenant->getTenantID() . '|' . $tenant->getTenantName() . '|' . $tenant->getContactPerson() .
					'|' . $tenant->getOfficePhone() . '|' . $tenant->getHomePhone() . '|' . $tenant->getMobilePhone();
					
				$szTenantInfo = str_replace(":", "<dquote />", $szTenantInfo);
				$szTenantInfo = str_replace("|", "<dpipe />", $szTenantInfo);
				
				echo 'admintenant:tenantinfo:' . $szTenantInfo;
			}
		}
	}
?>