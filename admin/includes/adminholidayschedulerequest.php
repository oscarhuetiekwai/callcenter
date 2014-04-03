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
		if ( isset($_GET['adminholidayschedule']) ) {
			if ( $_GET['adminholidayschedule'] == 'saveholiday' ) {

                                $desc = "";
                                $holiday = "";
                                $holidayfrom = "";
                                $holidayto = "";

                                if ( isset($_GET['holidayfrom']) ) {
                                    $holidayfrom = $_GET['holidayfrom'];
                                    //$holidayfromarr = explode(" ",$holidayfrom);

                                    //$holidaytimefromarr = explode(":",$holidayfromarr[1]);//time(hh:mm:ss)
                                    //$holidaytimefrom = $holidaytimefromarr[0] . ":" . $holidaytimefromarr[1];//time(hh:mm)
                                    //$holidaydatefrom = $holidayfromarr[0];//date(yyyy-mm-dd)
                                }

                                if ( isset($_GET['holidayto']) ) {
                                    $holidayto = $_GET['holidayto'];
                                    //$holidaytoarr = explode(" ",$holidayto);

                                    //$holidaytimetoarr = explode(":",$holidaytoarr[1]);//time(hh:mm:ss)
                                    //$holidaytimeto = $holidaytimetoarr[0] . ":" . $holidaytimetoarr[1];//time(hh:mm)
                                    //$holidaydateto = $holidaytoarr[0];//date(yyyy-mm-dd)
                                }

                                if ( isset($_GET['desc']) ) {
                                    $desc = $_GET['desc'];
                                }

                                if( $holidayfrom != "" && $holidayto !="" )
                                {
                                        if ( $mySQL ) {

                                            //$holiday = date("Y-m-d",$counterdate);//convert to date format(yyyy-mm-dd)

                                            $checkfromkeyquery = "SELECT * FROM holidays WHERE holidayfrom <= '" . $holidayfrom . "' and holidayto >= '" . $holidayfrom . "' LIMIT 1";

                                            $resultCheckFromKeySet = $mySQL->query($checkfromkeyquery);

                                            $objCheckFromKeyRow = $resultCheckFromKeySet->fetch_object();

                                            $checktokeyquery = "SELECT * FROM holidays WHERE holidayfrom <= '" . $holidayto . "' and holidayto >= '" . $holidayto . "' LIMIT 1";

                                            $resultCheckToKeySet = $mySQL->query($checktokeyquery);

                                            $objCheckToKeyRow = $resultCheckToKeySet->fetch_object();

                                            if ( !$objCheckFromKeyRow && !$objCheckToKeyRow )
                                            {
                                                $insertquery = "INSERT INTO holidays VALUES ('" . $holidayfrom . "','" . $holidayto . "','" . $desc . "')";

                                                $resultInsertSet = $mySQL->query($insertquery);

                                                echo 'adminholiday:saveholiday:1:';
                                            }
                                            else
                                            {
                                                if($objCheckFromKeyRow)
                                                {
                                                    $holidayfrom = str_replace(":", "<dquote />", $objCheckFromKeyRow->holidayfrom);
                                                    $holidayto = str_replace(":", "<dquote />", $objCheckFromKeyRow->holidayto);
                                                    echo 'adminholiday:saveholiday:0:From Date Time conflicts with another entry "' . $objCheckFromKeyRow->holidaydesc . '" ('.$holidayfrom.' to '.$holidayto.') !!!:';
                                                }
                                                else if($objCheckToKeyRow)
                                                    echo 'adminholiday:saveholiday:0:To Date Time conflicts with another entry "' . $objCheckFromKeyRow->holidaydesc . '" ('.$holidayfrom.' to '.$holidayto.') !!!:';
                                                else
                                                    echo 'adminholiday:saveholiday:0:Error, Please contact Administrator !!!:';
                                            }
                                        }
                                        else
                                        {
                                            echo 'adminholiday:saveholiday:0:Error Database Connection !!!:';
                                        }

                                }

			} else if ( $_GET['adminholidayschedule'] == 'deleteholiday' ) {

                                $resultCheckKeySet = "";
                                
                                if ( isset($_GET['values']) ) {
                                    $values = $_GET['values'];
                                    $valuesarr = explode(";",$values);
                                }

                                if ( $mySQL ) {

                                    for($i = 0 ; $i < sizeof($valuesarr) ; $i++)
                                    {
                                        $holidayrange = $valuesarr[$i];
                                        $holidayrangearr = explode("^",$holidayrange);

                                        if($holidayrangearr[0]!="" && $holidayrangearr[1]!="")
                                        {
                                            $deleteholidayquery = "DELETE FROM holidays WHERE holidayfrom = '" . trim($holidayrangearr[0]) . "' AND holidayto = '" . trim($holidayrangearr[1]) . "'";

                                            $resultCheckKeySet = $mySQL->query($deleteholidayquery);
                                        }
                                    }

                                    if ($resultCheckKeySet) {
                                         echo "adminholiday:deleteholiday:1:Successfully deleted record(s).";
                                    }
                                    else {
                                         echo "adminholiday:deleteholiday:0:Deletion error !!!!";
                                    }
                                }
                                else
                                {
                                    echo 'adminholiday:deleteholiday:0:Error Database Connection !!!:';
                                }
                                
			} else if ( $_GET['adminholidayschedule'] == 'updateholiday' ) {

                                $desc = "";
                                $holidayfrom = "";
                                $holidayto = "";

                                if ( isset($_GET['holidayfrom']) ) {
                                    $holidayfrom = $_GET['holidayfrom'];
                                    /*$holidayfromarr = explode(" ",$holidayfrom);

                                    $holidaytimefromarr = explode(":",$holidayfromarr[1]);//time(hh:mm:ss)
                                    $holidaytimefrom = $holidaytimefromarr[0] . ":" . $holidaytimefromarr[1];//time(hh:mm)
                                    $holidaydatefrom = $holidayfromarr[0];//date(yyyy-mm-dd)*/
                                }

                                if ( isset($_GET['holidayto']) ) {
                                    $holidayto = $_GET['holidayto'];
                                    /*$holidaytoarr = explode(" ",$holidayto);

                                    $holidaytimetoarr = explode(":",$holidaytoarr[1]);//time(hh:mm:ss)
                                    $holidaytimeto = $holidaytimetoarr[0] . ":" . $holidaytimetoarr[1];//time(hh:mm)
                                    $holidaydateto = $holidaytoarr[0];//date(yyyy-mm-dd)*/
                                }

                                if ( isset($_GET['desc']) ) {
                                    $desc = $_GET['desc'];
                                }

                                if ( $mySQL ) {
                                    
                                    $checkkeyquery = "SELECT * FROM holidays WHERE holidayfrom = '" . trim($holidayfrom) . "' AND holidayto = '" . trim($holidayto) . "'";

                                    $resultCheckKeySet = $mySQL->query($checkkeyquery);

                                    $objCheckKeyRow = $resultCheckKeySet->fetch_object();

                                    if ( $objCheckKeyRow )
                                    {
                                        $insertquery = "UPDATE holidays SET holidaydesc = '" . $desc . "' WHERE holidayfrom = '" . trim($holidayfrom) . "' AND holidayto = '" . trim($holidayto) . "'";

                                        $resultInsertSet = $mySQL->query($insertquery);

                                        echo 'adminholiday:updateholiday:1:';
                                    }
                                    else
                                    {
                                        echo 'adminholiday:updateholiday:0:Record does not exists !!!:';
                                    }
                                }
                                else
                                {
                                    echo 'adminholiday:updateholiday:0:Error Database Connection !!!:';
                                }

			} else if ( $_GET['adminholidayschedule'] == 'holidaylist' ) {

                                $nCnt = 1;

                                $holidaylist = "";
                            
				$holidaylistquery = "SELECT * FROM holidays order by holidayfrom,holidayto";

                                $resultHolidayListSet = $mySQL->query($holidaylistquery);

                                $objHolidayListRow = $resultHolidayListSet->fetch_object();

                                while ( $objHolidayListRow )
                                {
                                    /*$holiday = $objHolidayListRow->holidaydate;
                                    $holidayarr = explode("-",$holiday);
                                    $holiday = $holidayarr[1] . "/" .  $holidayarr[2] . "/" .  $holidayarr[0] ;*/

                                    $holidaylist .= "<label for='chkholiday" . $nCnt . "' onMouseOver='toggleHolidayDetails(\"holidaytoggle" . $objHolidayListRow->holidayfrom . "\");' onMouseOut='toggleHolidayDetails(\"holidaytoggle" . $objHolidayListRow->holidayfrom . "\");' onclick='onHolidayInfo(\"" . $objHolidayListRow->holidayfrom . "\",\"" . $objHolidayListRow->holidayto . "\")'><input type='checkbox' id='chkskill'" . $nCnt . "' name='chkholiday' value='" . $objHolidayListRow->holidayfrom . "^" . $objHolidayListRow->holidayto . "' /><b><font color='#009999'>" . $objHolidayListRow->holidaydesc . "</font></b></label>";
                                    $holidaylist .= "<div id='holidaytoggle" . $objHolidayListRow->holidayfrom . "' style='display:none; padding-left: 25px;'> From " . $objHolidayListRow->holidayfrom . " <br> To &nbsp;&nbsp;&nbsp;&nbsp; " . $objHolidayListRow->holidayto . "</div>";

                                    $objHolidayListRow = $resultHolidayListSet->fetch_object();

                                    $nCnt++;
                                }
				
				$holidaylist = str_replace(":", "<dquote />", $holidaylist);
				$holidaylist = str_replace("|", "<dpipe />", $holidaylist);
				echo 'adminholiday:holidaylist:' . $holidaylist;
			} else if ( $_GET['adminholidayschedule'] == 'holidayinfo' ) {

                                $holidayfrom = "";
                                $holidayto = "";

                                if ( isset($_GET['holidayfrom']) ) {
                                    $holidayfrom = $_GET['holidayfrom'];
                                }

                                if ( isset($_GET['holidayto']) ) {
                                    $holidayto = $_GET['holidayto'];
                                }

				$holidayinfoquery = "SELECT * FROM holidays WHERE holidayfrom = '" . trim($holidayfrom) . "' AND holidayto = '" . trim($holidayto) . "'";

                                $resultHolidayInfoSet = $mySQL->query($holidayinfoquery);

                                $objHolidayInfoRow = $resultHolidayInfoSet->fetch_object();

                                if ( $objHolidayInfoRow )
                                {
                                    $szHolidayInfo = $objHolidayInfoRow->holidaydesc . '|' . $objHolidayInfoRow->holidayfrom . '|' . $objHolidayInfoRow->holidayto;

                                    $szHolidayInfo = str_replace(":", "<dquote />", $szHolidayInfo);
                                    $szHolidayInfo = str_replace("|", "<dpipe />", $szHolidayInfo);

                                    echo 'adminholiday:holidayinfo:' . $szHolidayInfo;
                                }
                                else
                                {
                                    echo 'adminholiday:holidayinfo:record does not exists !!!';
                                }
				
			}
		}
	}
?>