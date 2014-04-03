<?php

        require_once('config.php');
	require_once('errhandler.php');

class PageCommon {
	private $m_nUserLevel = 0;
	
	function __construct() {
	}
	
	function __destruct() {
	}
	
	public function displayHeader($firstName,$exten,$userLevel,$tenant) {
		$szUserLevel = 'Administrator';
		if ($userLevel == 3 ) {
			$szUserLevel = $tenant . ' Supervisor';
		}
		
		$this->m_nUserLevel = $userLevel;
		echo '<table width="100%" height="78" border="0" cellspacing="0" cellpadding="0">';
		echo '<tr>';
    	echo '<td width="30" height="78" align="left">&nbsp;</td>';
    	echo '<td width="160" align="left"><img src="images/login.gif" alt="" width="296" height="26" /></td>';
    	echo '<td width="461" height="78">&nbsp;</td>';
    	echo '<td width="220" height="78" align="center"><table width="220" border="0" cellspacing="3" cellpadding="0">';
      	echo '<tr>';
        echo '<td width="48" height="38"><img src="images/user_pic.gif" width="42" height="38" /></td>';
        echo '<td width="163" valign="top"><font face="Arial, Helvetica, sans-serif">' . $szUserLevel . '<font size="2"><br />';
        echo 'Welcome, ' . $firstName . '<br />';
        echo 'Extension No. ' . $exten . '</font></font></td>';
      	echo '</tr>';
    	echo '</table></td>';
  		echo '</tr>';
		echo '</table>'; 
	}
	
	/*
		Views
		 DNIS
		 Virtual Queues
		 Agents
		 Summary
		
		Quality Monitoring
		 Play Call Logs
		
		Administration
		 Users
			Agent
			   Should be tied to supervisor
			Supervisor
			   Should be tied to which queues
		 Extensions
		   Supervisor
			  Display active and non-active
		 Skills
		 Queues
		 IVR
		 Trunk Groups
 */
	public function displayNavigation($topMenu, $subMenu) {

                $view = false;
                $qmonitor = false;
                $admin = false;
                $reports = false;
                $sub = false;

		echo '<table width="100%" height="66" border="0" cellspacing="0" cellpadding="0">';
		echo '<tr>';
    	echo '<td width="49" height="38" align="center" background="images/tile_blackbar.gif"><img src="images/spacer.gif" width="1" height="38" /></td>';

                $view = $this->accessAuthorityTop("Views");
                if ( $view == true)
                    if ( $topMenu == 'view' ) {
                    echo '<td width="96" height="38" align="center" bgcolor="#a11313"><b>';
                            echo '<font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Views</font></b></td>';
                    } else {
                    echo '<td width="96" height="38" align="center" background="images/tile_blackbar.gif"><b>';
                            echo '<font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="admin.php">Views</a></font></b></td>';
                    }
                    
		$qmonitor = $this->accessAuthorityTop("Quality Monitoring");
                if ( $qmonitor == true)
		if ( $topMenu == 'qmonitor' ) {
			echo '<td width="160" height="38" align="center" bgcolor="#a11313"><b>';
			echo '<font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif" >Quality Monitoring</font></b></td>';
		} else  {
			echo '<td width="160" height="38" align="center" background="images/tile_blackbar.gif" ><b>';
			echo '<font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminqmonitor.php"  >Quality Monitoring</a></font></b></td>';
		}

                $admin = $this->accessAuthorityTop("Administrations");
                if ( $admin == true)
		if ( $topMenu == 'admin' ) {
			echo '<td width="120" align="center" bgcolor="#a11313"><b>';
			echo '<font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Administrations</font></b></td>';
		} else {
	    	echo '<td width="120" align="center" background="images/tile_blackbar.gif"><b>';
			echo '<font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminusers.php">Administrations</a></font></b></td>';
		}

                $reports = $this->accessAuthorityTop("Reports");
                if ( $reports == true)
		if ( $topMenu == 'reports' ) {
    		echo '<td width="82" align="center"  bgcolor="#a11313"><b>';
			echo '<font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Reports</font></b></td>';
		} else {
    		echo '<td width="82" align="center" background="images/tile_blackbar.gif"><b>';
			echo '<font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="tpladminreports.php">Reports</a></font></b></td>';
			
		}

		echo '<td align="right" background="images/tile_blackbar.gif" ><b>';
		echo '<font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="#" id="lnkchangepwd" name="lnkchangepwd">Change Password</a></font></b></td>';
			
    	echo '<td width="100" height="39" background="images/tile_blackbar.gif" align="right"><a href="login.php"><img src="images/btnlogout.png" /></a>&nbsp;&nbsp;</td>';
  		echo '</tr>';
		echo '<tr>';
		echo '<td height="27" colspan="1" bgcolor="#a11313">&nbsp;</td>';
    	echo '<td height="27" colspan="6" bgcolor="#a11313">';
    	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
      	echo '<tr>';
		
		if ( $topMenu == 'view' && $view == true ) {
			/*if ( $subMenu == 'dnis' ) 
				echo '<td align="center"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">DNIS</font></b></td>';
			else 
				echo '<td align="center"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="admin.php">DNIS</a></font></b></td>';
				
			if ( $subMenu == 'vqueues' ) 
				echo '<td align="center"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">Virtual Queues</font></b></td>';
			else
	        	echo '<td align="center"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminvqueues.php">Virtual Queues</a></font></b></td>';
				
    	    echo '<td align="center"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Agents</font></b></td>';
        	echo '<td align="center"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Summary</font></b></td>'; */

                        $sub = $this->accessAuthoritySub("Views","Queues");
                        if ( $sub == true )
			if ( $subMenu == 'queues' )
				echo '<td align="left" style="width:100px;"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">Queues</font></b></td>';
			else 
				echo '<td align="left" style="width:100px;"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="admin.php">Queues</a></font></b></td>';

                        $sub = $this->accessAuthoritySub("Views","Agents");
                        if ( $sub == true )
			if ( $subMenu == 'agents' ) 
				echo '<td align="left" style="width:100px;"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">Agents</font></b></td>';
			else 
				echo '<td align="left" style="width:100px;"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminagents.php">Agents</a></font></b></td>';

                        $sub = $this->accessAuthoritySub("Views","Abandon");
                        if ( $sub == true )
                        if ( $subMenu == 'abandon' )
				echo '<td align="left" style="width:100px;"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">Abandon</font></b></td>';
			else
				echo '<td align="left" style="width:100px;"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminabandon.php">Abandon</a></font></b></td>';
				
			echo '<td align="left" style="width:100px;">&nbsp;</td>';
			echo '<td align="left" style="width:100px;">&nbsp;</td>';
			echo '<td align="left" style="width:100px;">&nbsp;</td>';
			echo '<td align="left" style="width:100px;">&nbsp;</td>';
				
		} else if ($topMenu == 'qmonitor' && $qmonitor == true ) {
				$sub = $this->accessAuthoritySub("Quality Monitoring","Call Logs");
                        if ( $sub == true )
			if ( $subMenu == 'call status' ) 
				echo '<td align="left" style="width:100px;"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">Call Logs</font></b></td>';
			else 
				echo '<td align="left" style="width:100px;"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminqmonitor.php">Call Logs</a></font></b></td>';

                       // $sub = $this->accessAuthoritySub("Quality Monitoring","Abandon Logs");
                       // if ( $sub == true )
                       // if ( $subMenu == 'abandon' )
				//echo '<td align="left" style="width:100px;"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">Abandon Logs</font></b></td>';
			//else
				//echo '<td align="left" style="width:100px;"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminabandon2.php">Abandon Logs</a></font></b></td>';
		} else if ($topMenu == 'admin' && $admin == true ) {

                        $sub = $this->accessAuthoritySub("Administrations","Users");
                        if ( $sub == true )
			if ( $subMenu == 'users' ) {
				echo '<td align="center" width="120px"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">Users</font></b></td>';
			} else {
				echo '<td align="center" width="120px"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminusers.php">Users</a></font></b></td>';
			}
			
			$sub = $this->accessAuthoritySub("Administrations","Extensions");
                        if ( $sub == true )
				if ( $subMenu == 'extensions' ) {
					echo '<td align="center" width="120px"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">Extensions</font></b></td>';
				} else {
					echo '<td align="center" width="120px"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminextension.php">Extensions</a></font></b></td>';
				}
			
			$sub = $this->accessAuthoritySub("Administrations","Skills");
                        if ( $sub == true )
			if ( $subMenu == 'skills' ) {
        		echo '<td align="center" width="120px"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">Skills</font></b></td>';
			} else {
				echo '<td align="center" width="120px"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminskills1.php">Skills</a></font></b></td>';
			}

                        $sub = $this->accessAuthoritySub("Administrations","Unavailable Codes");
                        if ( $sub == true )
			if ( $subMenu == 'unavailcodes' ) {
				echo '<td align="center" width="120px"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">Unavailable Codes</font></b></td>';
			} else {
				echo '<td align="center" width="120px"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminunavail.php">Unavailable Codes</a></font></b></td>';
			}

                        $sub = $this->accessAuthoritySub("Administrations","Wrap-ups");
                        if ( $sub == true )
			if ( $subMenu == 'wrapups' ) {
        		echo '<td align="center" width="120px"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">Wrap-ups</font></b></td>';
			} else {
				echo '<td align="center" width="120px"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminwrapups.php">Wrap-ups</a></font></b></td>';
			}
			
			$sub = $this->accessAuthoritySub("Administrations","Queues");
                        if ( $sub == true )
			if ( $subMenu == 'queues' ) {
				echo '<td align="center" width="120px"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">Queues</font></b></td>';
			} else {
				echo '<td align="center" width="120px"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminqueues.php">Queues</a></font></b></td>';
			}
			
			/* if ( $subMenu == 'outbounds' ) {
				echo '<td align="center" width="120px"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">Outbounds</font></b></td>';
			} else {
				echo '<td align="center" width="120px"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminoutbounds.php">Outbounds</a></font></b></td>';
			}*/

                        $sub = $this->accessAuthoritySub("Administrations","User Level");
                        if ( $sub == true )
                        if ( $subMenu == 'userlevel' ) {
				echo '<td align="center" width="120px"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">User Level</font></b></td>';
			} else {
				echo '<td align="center" width="120px"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminuserlevel.php">User Level</a></font></b></td>';
                        }

                        $sub = $this->accessAuthoritySub("Administrations","Holiday Schedule");
                        if ( $sub == true )
                        if ( $subMenu == 'holiday' ) {
				echo '<td align="center" width="120px"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">Holiday Schedule</font></b></td>';
			} else {
				echo '<td align="center" width="120px"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminholidayschedule.php">Holiday Schedule</a></font></b></td>';
			}

			$sub = $this->accessAuthoritySub("Administrations","Tenants");
                        if ( $sub == true )
				if ( $subMenu == 'tenants' ) {
					echo '<td align="center" width="120px"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">Tenants</font></b></td>';
				} else {
					echo '<td align="center" width="120px"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="admintenant.php">Tenants</a></font></b></td>';
				}
			
			/*if ( $subMenu == 'settings' ) {
				echo '<td align="center" width="120px"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">Settings</font></b></td>';
			} else {
				echo '<td align="center" width="120px"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminsettings.php">Settings</a></font></b></td>';
			} */

			//echo '<td align="center" width="120px"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="admintrunks.php">Trunk</a></font></b></td>';
			echo '<td>&nbsp;</td>';
		} else if ($topMenu == 'reports' && $reports == true) {
			echo '<td align="center"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></b></td>';
		}
		
      	echo '</tr>';
    	echo '</table></td>';
  		echo '</tr>';
		echo '</table>';

 	}
	
	public function displayMenu($tenantID) {
		echo '<p align="center">';
		echo '<a href="admin.php">Home</a>&nbsp;|&nbsp;';
		
		if ( $tenantID == "0" ) {
			echo '<a href="tenant.php">Tenants</a>&nbsp;|&nbsp;';
			echo '<a href="extension.php">Extensions</a>&nbsp;|&nbsp;';
		}
		
		echo '<a href="user.php">Users</a>&nbsp;|&nbsp;';
		echo '<a href="skill.php">Skills</a>&nbsp;|&nbsp;';
		echo '<a href="queue.php">Queues</a>';		
		echo '</p>';
	}
	
	public function displayDivChangePwd() {
		echo '<div id="changepwd" name="changepwd" class="divchangepwd" title="Change Password">';
		echo '	<p class="validateTips">All form fields are required.</p>';
		echo '	<form>';
		echo '		<fieldset>';
		echo '			<label for="txtoldpwd">Old Password</label>';
		echo '			<input type="password" name="txtoldpwd" id="txtoldpwd" value="" class="text ui-widget-content ui-corner-all" />';
		echo '			<label for="txtnewpwd">New Password</label>';
		echo '			<input type="password" name="txtnewpwd" id="txtnewpwd" value="" class="text ui-widget-content ui-corner-all" />';
		echo '			<label for="txtverifypwd">Verify Password</label>';
		echo '			<input type="password" name="txtverifypwd" id="txtverifypwd" value="" class="text ui-widget-content ui-corner-all" />';
		echo '		</fieldset>';
		echo '	</form>';
		echo '</div>';
	}
	
	public function displayScriptChangePwd() {
		echo '<script language="JavaScript">';
		echo '	$(document).ready(function() {';
		echo '	$( "#changepwd:ui-dialog" ).dialog( "destroy" );';
		echo '';
		echo '	var txtoldpwd = $( "#txtoldpwd" ),';
		echo '	txtnewpwd = $( "#txtnewpwd" ),';
		echo '	txtverifypwd = $( "#txtverifypwd" ),';
		echo '	allFields = $( [] ).add( txtoldpwd ).add( txtnewpwd ).add( txtverifypwd ),';
		echo '	tips = $( ".validateTips" );';
		echo '   ';
		echo '	function updateTips( t ) {';
		echo '		tips';
		echo '			.text( t )';
		echo '			.addClass( "ui-state-highlight" );';
		echo '			setTimeout(function() {';
		echo '				tips.removeClass( "ui-state-highlight", 1500 );';
		echo '			}, 500 );';
		echo '	}';
		echo '';
		echo '	function checkLength( o, n, min, max ) {';
		echo '		if ( o.val().length > max || o.val().length < min ) {';
		echo '			o.addClass( "ui-state-error" );';
		echo '			updateTips( "Length of " + n + " must be between " +';
		echo '				min + " and " + max + "." );';
		echo '			return false;';
		echo '		} else {';
		echo '			return true;';
		echo '		}';
		echo '	}';
		echo '';
		echo '	function checkRegexp( o, regexp, n ) {';
		echo '		if ( !( regexp.test( o.val() ) ) ) {';
		echo '			o.addClass( "ui-state-error" );';
		echo '			updateTips( n );';
		echo '			return false;';
		echo '		} else {';
		echo '			return true;';
		echo '		}';
		echo '	}';
		echo '';
		echo '	$("#lnkchangepwd").click(function()';
		echo '	{';
		echo '		$( "#changepwd" ).dialog( "open" );';
		echo '	});';
		echo '';
		echo '	$( "#changepwd" ).dialog({';
		echo '		autoOpen: false,';
		echo '		height: 350,';
		echo '		width: 350,';
		echo '		modal: true,';
		echo '		buttons: {';
		echo '			"Update password": function() {';
		echo '				var bValid = true;';
		echo '				allFields.removeClass( "ui-state-error" );';
		echo '';
		echo '				bValid = bValid && checkLength( txtoldpwd, "Old Password", 4, 16 );';
		echo '				bValid = bValid && checkLength( txtnewpwd, "New Password", 4, 16 );';
		echo '				bValid = bValid && checkLength( txtverifypwd, "Verify Password", 4, 16 );';
		echo '';
		echo '				bValid = bValid && checkRegexp( txtoldpwd, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );';
		echo '				bValid = bValid && checkRegexp( txtnewpwd, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );';
		echo '				bValid = bValid && checkRegexp( txtverifypwd, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );';
		echo '				';
		echo '';
		echo '				if ( bValid ) {';
		echo '					if ( txtverifypwd.val() == txtnewpwd.val() ) {';
		//echo '						addHttpRequest("changepwd", "oldpass=" + txtoldpwd.val() + "&newpass=" + txtnewpwd.val() );';
		echo '						$.get("includes/adminsrequest.php",';
		echo '						{';
		echo '							changepwd: "1",';
		echo '							oldpass: txtoldpwd.val(),';
		echo '							newpass: txtnewpwd.val()';
		echo '						},';
		echo '						function (data) ';
		echo '						{';
		echo '							var arrchangepwd = data.split(":");';
		echo '';
		echo '							return ( arrchangepwd[1] == "1" ) ? updateTips("Password successfully changed!!!") : ';
		echo '								updateTips("Invalid password!!!"); ';
		echo '						}';
		echo '						);';
		//echo '						$( this ).dialog( "close" );';
		echo '					} else {';
		echo '						updateTips("New Password and Verify Password should be the same. Please re-type the new password.");';
		echo '					}';
		echo '				}';
		echo '			},';
		echo '			Cancel: function() {';
		echo '				$( this ).dialog( "close" );';
		echo '			}';
		echo '		},';
		echo '		close: function() {';
		echo '			allFields.val( "" ).removeClass( "ui-state-error" );';
		echo '		}';
		echo '	});'; 
		echo '';
		echo '});'; 
		echo '</script>';
	}

        public function accessAuthorityTop($topmenu){
            $mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
            $authority = false;

            if ( $mySQL ) {

                $topmenuquery = "SELECT * FROM userslevel WHERE topmenu = '" . $topmenu ."'";

                $resultTopMenuSet = $mySQL->query($topmenuquery);

                $objTopMenuRow = $resultTopMenuSet->fetch_object();

                while ( $objTopMenuRow ) {
                    $usrlvlarr = explode(",",$objTopMenuRow->userslevel);

                    foreach ($usrlvlarr as $arrvalue)
                        if($arrvalue == $this->m_nUserLevel && $this->m_nUserLevel!=0)
                            $authority = true;

                    if($authority == false)
                        $objTopMenuRow = $resultTopMenuSet->fetch_object();
                    else
                        break;
                }
            }
            return $authority;
        }

        public function accessAuthoritySub($topmenu,$submenu){
            $mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
            $authority = false;

            if ( $mySQL ) {

                $submenuquery = "SELECT * FROM userslevel WHERE topmenu = '" . $topmenu ."' AND submenu = '" . $submenu . "'";

                $resultSubMenuSet = $mySQL->query($submenuquery);

                $objSubMenuRow = $resultSubMenuSet->fetch_object();

                $usrlvlarr = explode(",",$objSubMenuRow->userslevel);

                foreach ($usrlvlarr as $arrvalue)
                    if($arrvalue == $this->m_nUserLevel && $this->m_nUserLevel!=0)
                        $authority = true;
            }
            return $authority;
        }
        
        public function displayChatBoxList($webAdminSession,$webAdminUser,$webAdminID) {
                require_once('config.php');
                require_once('errhandler.php');
                require_once('socket.php');
                require_once('serverresponse.php');

                $mySQL = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
                $socket = new ClientSocket(SERVER_IP,SERVER_PORT);
                
                if ( $socket->connect() ) {
                    $chatResponseArray = explode(":",$socket->sendMessage('<getchatcontactlist><sessionid>' . $webAdminSession . '</sessionid></getchatcontactlist>'));
                    
                    $chatListArray = explode("|",$chatResponseArray[2]);

                    $supervisorlist = '';
                    $agentlist = '';
                    $myteamlist = '';
                    $supervisorcount = 0;
                    $agentcount = 0;
                    $myteamcount = 0;

                    echo '<div id="chatboxlist" name="chatbox" class="chatboxlist">';
                    echo '<div class="chatboxhead"><a href="javascript:void(0)" class="headertitle" onclick="toggleChatBox(\'chatlist\',\'\')"><div id="chatheader" style="float: left; width: 140px;">Chat List (' . (sizeof($chatListArray) - 2) . ')</div></a>
                        </div>';
                    echo '<div id="chatlist" style="display:none;" class="chatlist">';

                    foreach ($chatListArray as $listArrvalue)
                    {
                        if($listArrvalue != "")
                        {
                            $chatIdName = explode("^",$listArrvalue);
                            
                            if($webAdminUser != $chatIdName[1])
                            {
                                if(isset($chatIdName[3])){
                                    $chatIdName[3] = ucfirst($chatIdName[3]);
                                }
                                if(isset($chatIdName[2]) && isset($chatIdName[5])){
                                    if($chatIdName[2] == 2)//agent
                                    {
                                        if(trim($chatIdName[5]) == trim($webAdminID))//my team
                                        {
                                            $myteamlist .= '<div id="contact' . $chatIdName[0] . '" name="groupmyteamcontact"><a href="javascript:void(0)" class="chatwithname" onclick="javascript:chatWith(\'' . $chatIdName[0] . '\',\'' . $chatIdName[3] . '\',\'chat\')"><div class="chatname" > ' . $chatIdName[3] . ' </div></a></div>';
                                            $myteamcount++;
                                        } else {
                                            $agentlist .= '<div id="contact' . $chatIdName[0] . '" name="groupagentcontact"><a href="javascript:void(0)" class="chatwithname" onclick="javascript:chatWith(\'' . $chatIdName[0] . '\',\'' . $chatIdName[3] . '\',\'chat\')"><div class="chatname" > ' . $chatIdName[3] . ' </div></a></div>';
                                            $agentcount++;
                                        }
                                    }
                                    else if($chatIdName[2] == 3 || $chatIdName[2] == 5)//supervisor
                                    {
                                        $supervisorlist .= '<div id="contact' . $chatIdName[0] . '" name="groupsupervisorcontact"><a href="javascript:void(0)" class="chatwithname" onclick="javascript:chatWith(\'' . $chatIdName[0] . '\',\'' . $chatIdName[3] . '\',\'chat\')"><div class="chatname" > ' . $chatIdName[3] . ' </div></a></div>';;
                                        $supervisorcount++;
                                    }
                                }
                            }
                        }
                    }
                    /*echo '<div id="groupsupervisorheader" class="grouptitlediv"><a href="javascript:void(0)" onclick="toggleChatBox(\'groupsupervisor\',\'\'); toggleGroup(\'groupsupervisortitle\')" class="grouptitle"><div id="groupsupervisortitle" style="float: left; width: 150px;"> - Supervisor ('.$supervisorcount.')</div></a>
                        <div style="float: right;"><a href="javascript:void(0)" onclick="javascript:chatWith(\'broadcastall\',\'Broadcast To Supervisors\',\'broadcastsupervisor\')" onmouseover="document.broadcastsupervisor.src=\'images/broadcastpink.png\'" onmouseout="document.broadcastsupervisor.src=\'images/broadcastred.png\'"><img name="broadcastsupervisor" src="images/broadcastred.png" alt="Broadcast to Supervisors" title="Broadcast to Supervisors" height="18" width="18"/></a>&nbsp;&nbsp;</div></div>';*/
                    /* backup for broadcast icon
                     * echo '<div id="groupsupervisorheader" class="grouptitlediv"><table><tr><td><a href="javascript:void(0)" onclick="toggleChatBox(\'groupsupervisor\',\'\'); toggleGroup(\'groupsupervisortitle\')" class="grouptitle"><div id="groupsupervisortitle" style="width: 147px;"> - Supervisor ('.$supervisorcount.')</div></a></td>
                        <td><a href="javascript:void(0)" onclick="javascript:chatWith(\'broadcastall\',\'Broadcast To Supervisors\',\'broadcastsupervisor\')" onmouseover="document.broadcastsupervisor.src=\'images/broadcastpink.png\'" onmouseout="document.broadcastsupervisor.src=\'images/broadcastred.png\'"><img name="broadcastsupervisor" src="images/broadcastred.png" alt="Broadcast to Supervisors" title="Broadcast to Supervisors" height="18" width="18"/></a></td></tr></table></div>';
                    echo '<div id="groupsupervisor" name="chatgroup" class="group">';
                    echo $supervisorlist;
                    echo '</div>';
                    echo '<div id="groupagentheader" class="grouptitlediv"><table><tr><td><a href="javascript:void(0)" onclick="toggleChatBox(\'groupagent\',\'\'); toggleGroup(\'groupagenttitle\')" class="grouptitle"><div id="groupagenttitle" style="width: 147px;"> - Agent ('.$agentcount.')</div></a></td>
                        <td><a href="javascript:void(0)" onclick="javascript:chatWith(\'broadcastall\',\'Broadcast To Agents\',\'broadcastagent\')" onmouseover="document.broadcastagent.src=\'images/broadcastpink.png\'" onmouseout="document.broadcastagent.src=\'images/broadcastred.png\'"><img name="broadcastagent" src="images/broadcastred.png" alt="Broadcast to Agents" title="Broadcast to Agents" height="18" width="18"/></a></td></tr></table></div>';
                    echo '<div id="groupagent" name="chatgroup" class="group">';
                    echo $agentlist;
                    echo '</div>';
                    echo '<div id="' . $webAdminID . '" class="grouptitlediv" name="groupmyteamheadername"><table><tr><td><a href="javascript:void(0)" onclick="toggleChatBox(\'groupmyteam\',\'\'); toggleGroup(\'groupmyteamtitle\')" class="grouptitle"><div id="groupmyteamtitle" style="width: 147px;"> - My Team ('.$myteamcount.')</div></a></td>
                        <td><a href="javascript:void(0)" onclick="javascript:chatWith(\'broadcastall\',\'Broadcast To My Team\',\'broadcastmyteam\')" onmouseover="document.broadcastmyteam.src=\'images/broadcastpink.png\'" onmouseout="document.broadcastmyteam.src=\'images/broadcastred.png\'"><img name="broadcastmyteam" src="images/broadcastred.png" alt="Broadcast to My Team" title="Broadcast to My Team" height="18" width="18"/></a></td></tr></table></div>';
                    echo '<div id="groupmyteam" name="chatgroup" class="group">';
                    echo $myteamlist;
                    echo '</div>';
                    echo '</div></div>';*/
                    
                    //without broadcast
                    echo '<div id="groupsupervisorheader" class="grouptitlediv"><table><tr><td><a href="javascript:void(0)" onclick="toggleChatBox(\'groupsupervisor\',\'\'); toggleGroup(\'groupsupervisortitle\')" class="grouptitle"><div id="groupsupervisortitle" style="width: 147px;"> - Supervisor ('.$supervisorcount.')</div></a></td>
                        </tr></table></div>';
                    echo '<div id="groupsupervisor" name="chatgroup" class="group">';
                    echo $supervisorlist;
                    echo '</div>';
                    echo '<div id="groupagentheader" class="grouptitlediv"><table><tr><td><a href="javascript:void(0)" onclick="toggleChatBox(\'groupagent\',\'\'); toggleGroup(\'groupagenttitle\')" class="grouptitle"><div id="groupagenttitle" style="width: 147px;"> - Agent ('.$agentcount.')</div></a></td>
                        </tr></table></div>';
                    echo '<div id="groupagent" name="chatgroup" class="group">';
                    echo $agentlist;
                    echo '</div>';
                    echo '<div id="' . $webAdminID . '" class="grouptitlediv" name="groupmyteamheadername"><table><tr><td><a href="javascript:void(0)" onclick="toggleChatBox(\'groupmyteam\',\'\'); toggleGroup(\'groupmyteamtitle\')" class="grouptitle"><div id="groupmyteamtitle" style="width: 147px;"> - My Team ('.$myteamcount.')</div></a></td>
                        </tr></table></div>';
                    echo '<div id="groupmyteam" name="chatgroup" class="group">';
                    echo $myteamlist;
                    echo '</div>';
                    echo '</div></div>';

                }
        }
}

/*class authorityAccess extends PageCommon
{
    function accessAuthorityTop($topmenu){
                        return true;
    }
}*/

?>
