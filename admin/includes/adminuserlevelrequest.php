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

        $mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
	
	if ( isset($_SESSION['WEB_ADMIN_SESSION']) ) {
		if ( isset($_GET['adminuserlevel']) ) {
			if ( $_GET['adminuserlevel'] == 'userlevellist' ) {
                            $szAuthorityAccess = "";

                            if ( isset($_GET['userlevelid']) ) {
                                $userlevel = $_GET['userlevelid'];
                            }

                            if($userlevel==1)
                                $szAuthorityAccess = "<h2 style='font-size: medium;font-family: Arial, Helvetica, sans-serif;text-decoration: underline;'>User Level: &nbsp;Guest</h3>";
                            else if($userlevel==2)
                                $szAuthorityAccess = "<h2 style='font-size: medium;font-family: Arial, Helvetica, sans-serif;text-decoration: underline;'>User Level: &nbsp;Agent</h2>";
                            else if($userlevel==3)
                                $szAuthorityAccess = "<h2 style='font-size: medium;font-family: Arial, Helvetica, sans-serif;text-decoration: underline;'>User Level: &nbsp;Supervisor</h2>";
                            else if($userlevel==4)
                                $szAuthorityAccess = "<h2 style='font-size: medium;font-family: Arial, Helvetica, sans-serif;text-decoration: underline;'>User Level: &nbsp;Admin</h2>";
                            else if($userlevel==5)
                                $szAuthorityAccess = "<h2 style='font-size: medium;font-family: Arial, Helvetica, sans-serif;text-decoration: underline;'>User Level: &nbsp;Team Leader</h2>";

                            if ( $mySQL ) {
                                
                                $topmenuquery = "SELECT DISTINCT(topmenu) FROM userslevel";

                                $resultTopMenuSet = $mySQL->query($topmenuquery);

                                $objTopMenuRow = $resultTopMenuSet->fetch_object();

                                while ( $objTopMenuRow ) {

                                    $checked = false;

                                    if($userlevel==4 || $userlevel==2)
                                        $szAuthorityAccess .= "<h3 style='color: #666666;'><input type='checkbox' name='topmenu".$objTopMenuRow->topmenu."'onClick='checkBoxes(this,\"chk".$objTopMenuRow->topmenu."\")' DISABLED/>" . $objTopMenuRow->topmenu . "</h3>";
                                    else
                                        $szAuthorityAccess .= "<h3 style='color: #666666;'><input type='checkbox' name='topmenu".$objTopMenuRow->topmenu."'onClick='checkBoxes(this,\"chk".$objTopMenuRow->topmenu."\")' />" . $objTopMenuRow->topmenu . "</h3>";

                                    $submenuquery = "SELECT * FROM userslevel WHERE topmenu = '" . $objTopMenuRow->topmenu . "'";

                                    $resultSubMenuSet = $mySQL->query($submenuquery);

                                    $objSubMenuRow = $resultSubMenuSet->fetch_object();

                                    $szAuthorityAccess .="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

                                    while ( $objSubMenuRow ) {
                                        $usrlvlarr = explode(",",$objSubMenuRow->userslevel);
                                        $uselvlid = $objTopMenuRow->topmenu . ":" . $objSubMenuRow->submenu;

                                        foreach ($usrlvlarr as $arrvalue)
                                            if($arrvalue == $userlevel && $userlevel!=0)
                                                $checked = true;
                                        if($objSubMenuRow->submenu != "User Level")
                                            if($checked == true)
                                                if($userlevel==4)
                                                    $szAuthorityAccess .= "<input type='checkbox' id='chk" . $uselvlid . "' name='chk" . $objTopMenuRow->topmenu . "' value='" . $uselvlid . "' DISABLED CHECKED/><b><font color='#009999'>" . $objSubMenuRow->submenu. "</font>&nbsp;&nbsp;&nbsp;&nbsp;";
                                                else
                                                    $szAuthorityAccess .= "<input type='checkbox' id='chk" . $uselvlid . "' name='chk" . $objTopMenuRow->topmenu . "' value='" . $uselvlid . "' CHECKED/><b><font color='#009999'>" . $objSubMenuRow->submenu. "</font>&nbsp;&nbsp;&nbsp;&nbsp;";
                                            else if($userlevel==2)
                                                $szAuthorityAccess .= "<input type='checkbox' id='chk" . $uselvlid . "' name='chk" . $objTopMenuRow->topmenu . "' value='" . $uselvlid . "' DISABLED/><b><font color='#009999'>" . $objSubMenuRow->submenu. "</font>&nbsp;&nbsp;&nbsp;&nbsp;";
                                            else
                                                $szAuthorityAccess .= "<input type='checkbox' id='chk" . $uselvlid . "' name='chk" . $objTopMenuRow->topmenu . "' value='" . $uselvlid . "' /><b><font color='#009999'>" . $objSubMenuRow->submenu. "</font>&nbsp;&nbsp;&nbsp;&nbsp;";

                                        $objSubMenuRow = $resultSubMenuSet->fetch_object();
                                        $checked = false;
                                    }

                                    $objTopMenuRow = $resultTopMenuSet->fetch_object();
                                }
                            }

                            $szAuthorityAccess = str_replace(":", "<dquote />", $szAuthorityAccess);
                            $szAuthorityAccess = str_replace("|", "<dpipe />", $szAuthorityAccess);

                            echo 'adminuserlevel:accessauthority:'.$userlevel.':' . $szAuthorityAccess;

			} else if ( $_GET['adminuserlevel'] == 'saveauthorityaccess' ) {

                            if ( isset($_GET['userlevelid']) ) {
                                $userlevel = $_GET['userlevelid'];
                            }

                            if ( isset($_GET['accessauthorityvalues']) ) { 
                                $accessauthorityvalues = $_GET['accessauthorityvalues'];

                                $accautharr = explode(",",$accessauthorityvalues);
                                foreach ($accautharr as $arrvalue)
                                {
                                        $topsubmenuarr = explode(":",$arrvalue);

                                        if ( $mySQL ) {

                                            $updateCheck = "";
                                            $userlevelvalues = "";
                                            $exist = false;

                                            $menuquery = "SELECT * FROM userslevel WHERE topmenu = '".$topsubmenuarr[0]."' AND submenu = '".$topsubmenuarr[1]."'";

                                            $resultMenuSet = $mySQL->query($menuquery);

                                            $objMenuRow = $resultMenuSet->fetch_object();
                                            if($objMenuRow)
                                            {
                                                if($topsubmenuarr[2] == "checked")
                                                    if($objMenuRow->userslevel != "")
                                                    {
                                                        $usrlvlarr = explode(",",$objMenuRow->userslevel);
                                                        foreach ($usrlvlarr as $usrlvlarrvalue)
                                                            if($usrlvlarrvalue == $userlevel && $userlevel!=0)
                                                                $exist = true;

                                                            if ($exist == true)
                                                            {
                                                                $userlevelvalues = $objMenuRow->userslevel;
                                                            }
                                                            else
                                                            {
                                                                $userlevelvalues = $objMenuRow->userslevel . "," . $userlevel;
                                                            }
                                                    }
                                                    else
                                                    {
                                                        $userlevelvalues = $userlevel;
                                                    }
                                                else if($topsubmenuarr[2] == "unchecked")
                                                {
                                                    if($objMenuRow->userslevel != "")
                                                    {
                                                        $usrlvlarr = explode(",",$objMenuRow->userslevel);
                                                        foreach ($usrlvlarr as $usrlvlarrvalue)
                                                            if($usrlvlarrvalue != $userlevel && $userlevel!=0)
                                                                if($userlevelvalues == "")
                                                                    $userlevelvalues = $usrlvlarrvalue;
                                                                else
                                                                    $userlevelvalues .= "," . $usrlvlarrvalue;
                                                    }
                                                }

                                                $updatequery = "UPDATE userslevel SET userslevel = '".$userlevelvalues."' WHERE topmenu = '".$topsubmenuarr[0]."' AND submenu = '".$topsubmenuarr[1]."'";

                                                $resultUpdate = $mySQL->query($updatequery);

                                                if($resultUpdate)
                                                    $updateCheck = '1';
                                                else
                                                    $updateCheck = '0';
                                            }
                                        }
                                }
                            }
                            echo 'adminuserlevel:saveuserlevel:' . $updateCheck;
                        }
		}
	}
?>