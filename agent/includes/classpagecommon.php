<?php

class PageCommon {
	function __construct() {
	}
	
	function __destruct() {
	}
	
	public function displayHeader($firstName,$exten,$tenant) {
		echo '<table width="100%"border="0" cellspacing="0" cellpadding="0">';
     	echo '<tr height="48" valign="middle">';
		echo '<td width="30" align="left">&nbsp;</td>';
		echo '<td width="160" align="left"><img src="images/login.gif" alt="" width="296" height="26" /></td>';
		echo '<td width="461">&nbsp;</td>';
		echo '<td width="220" align="center"><table width="220" border="0" cellspacing="3" cellpadding="0">';
		echo '  <tr valign="center">';
		echo '	<td width="58" height="38" valign="middle"><font face="Arial, Helvetica, sans-serif" size="4">' . $tenant . '&nbsp;&nbsp;|&nbsp;&nbsp;</font></td>';
		echo '	<td width="163"><font face="Arial, Helvetica, sans-serif" size="2">';
		echo '	  Welcome, ' . $firstName . '<br />';
		echo '	Extension No. ' . $exten . '<br /><div id="divstatus"></div></font></td>';
		echo '  </tr>';
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
		echo '<table width="100%" height="66" border="0" cellspacing="0" cellpadding="0">';
		echo '<tr>';
    	echo '<td width="49" height="38" align="center" background="images/tile_blackbar.gif"><img src="images/spacer.gif" width="1" height="38" /></td>';
		
		if ( $topMenu == 'view' ) {
    		echo '<td width="96" height="38" align="center" bgcolor="#a11313"><b>';
			echo '<font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">View</font></b></td>';			
		} else {
    		echo '<td width="96" height="38" align="center" background="images/tile_blackbar.gif"><b>';
			echo '<font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="admin.php">View</a></font></b></td>';
		}
		
		if ( $topMenu == 'qmonitor' ) {
			echo '<td width="160" height="38" align="center" bgcolor="#a11313"><b>';
			echo '<font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Quality Monitoring</font></b></td>';
		} else  {
			echo '<td width="160" height="38" align="center" background="images/tile_blackbar.gif"><b>';
			echo '<font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="qmonitor.php">Quality Monitoring</a></font></b></td>';
		}
		
		if ( $topMenu == 'admin' ) {
			echo '<td width="120" align="center" bgcolor="#a11313"><b>';
			echo '<font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Administrations</font></b></td>';
		} else {
	    	echo '<td width="120" align="center" background="images/tile_blackbar.gif"><b>';
			echo '<font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminusers.php">Administrations</a></font></b></td>';
		}
		
		if ( $topMenu == 'reports' ) {
    		echo '<td width="82" align="center"  bgcolor="#a11313"><b>';
			echo '<font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Reports</font></b></td>';
		} else {
    		echo '<td width="82" align="center" background="images/tile_blackbar.gif"><b>';
			echo '<font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="reports.php">Reports</a></font></b></td>';			
		}
		
    	echo '<td width="167" align="center" background="images/tile_blackbar.gif"><b>';
		echo '<font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></b></td>';
    	echo '<td width="566" height="39" background="images/tile_blackbar.gif">&nbsp;</td>';
  		echo '</tr>';
		echo '<tr>';
		echo '<td height="27" colspan="1" bgcolor="#a11313">&nbsp;</td>';
    	echo '<td height="27" colspan="6" bgcolor="#a11313">';
    	echo '<table width="300" border="0" cellspacing="0" cellpadding="0">';
      	echo '<tr>';
		
		if ( $topMenu == 'view' ) {
	        echo '<td align="center"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Queues</font></b></td>';
    	    echo '<td align="center"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Agents</font></b></td>';
        	echo '<td align="center"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Summary</font></b></td>';
		} else if ($topMenu == 'qmonitor' ) {
			echo '<td align="center"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Call Logs</font></b></td>';
		} else if ($topMenu == 'admin' ) {
			if ( $subMenu == 'users' ) {
				echo '<td align="center"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">Users</font></b></td>';
			} else {
				echo '<td align="center"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminusers.php">Users</a></font></b></td>';
			}
			
    	    echo '<td align="center"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminextens.php">Extensions</a></font></b></td>';
			if ( $subMenu == 'skills' ) {
        		echo '<td align="center"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">Skills</font></b></td>';
			} else {
				echo '<td align="center"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminskills.php">Skills</a></font></b></td>';
			}
			
			if ( $subMenu == 'queues' ) {
				echo '<td align="center"><b><font color="#FFFF00" size="2" face="Arial, Helvetica, sans-serif">Queues</font></b></td>';
			} else {
				echo '<td align="center"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="adminqueues.php">Queues</a></font></b></td>';
			}
			
			echo '<td align="center"><b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><a href="admintrunks.php">Trunk</a></font></b></td>';
		} else if ($topMenu == 'reports' ) {
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
        
        public function displayChatBoxList($webAgentSession,$webAgentUser,$webAgentID) {
                require_once('includes/socket.php');//chat
                require_once('includes/serverresponse.php');//chat
                
                //chat
                $socket = new ClientSocket(SERVER_IP,SERVER_PORT);

                if ( $socket->connect() ) {
                    $chatResponseArray = explode(":",$socket->sendMessage('<getchatcontactlist><sessionid>' . $webAgentSession . '</sessionid></getchatcontactlist>'));
                    
                    $chatListArray = explode("|",$chatResponseArray[2]);
                    
                    echo '<div id="chatboxlist" name="chatbox" class="chatboxlist">';
                    echo '<div class="chatboxhead"><a href="javascript:void(0)" class="headertitle" onclick="toggleChatBox(\'chatlist\',\'\')"><div id="chatheader">Chat List (' . (sizeof($chatListArray) - 1) . ')</div></a></div>';
                    echo '<div id="chatlist" style="display:none;" class="chatlist">';
                    foreach ($chatListArray as $listArrvalue)
                    {
                        if($listArrvalue != "")
                        {
                            $chatIdName = explode("^",$listArrvalue);

                            if($webAgentUser != $chatIdName[1])
                            {
                                $contactName = ucfirst($chatIdName[1]);
                                echo '<div id="contact' . $chatIdName[0] . '"><a href="javascript:void(0)" class="chatwithname" onclick="javascript:chatWith(\'' . $chatIdName[0] . '\',\'' . $contactName . '\')"><div class="chatname" > ' . $chatIdName[3] . ' </div></a></div>';
                            }
                        }
                    }

                    echo '</div></div>';

                }
        }
}

?>
