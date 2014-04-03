<?php
//no  cache headers 
header("Expires: Mon, 26 Jul 2014 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i(worry)") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>


<?php
	session_start();
	require_once('includes/config.php'); 
	//require_once('includes/errhandler.php');
	require_once('includes/socket.php');
	require_once('includes/serverresponse.php');
	


	
	$socketError = NULL;

    if ( isset($_SESSION['WEB_AGENT_SESSION']) ) {
		try {
			$socketLogout = new ClientSocket(SERVER_IP,SERVER_PORT);
			
			if ( $socketLogout->connect() ) {
				$socketLogout->sendMessage('<logout><session>' . $_SESSION['WEB_AGENT_SESSION'] . 
					'</session></logout>');
			} else {
				$socketError = 'Unable to connect to server.';
			}
		} catch (Exception $e) {
			$socketError = 'Unable to connect to server. ' . $e->getMessage();
		}
		
	    unset($_SESSION['WEB_AGENT_SESSION']);
		unset($_SESSION['WEB_AGENT_FNAME']);
		unset($_SESSION['WEB_AGENT_LNAME']);
		unset($_SESSION['WEB_AGENT_TENANT']);
		unset($_SESSION['WEB_AGENT_USER']);
		unset($_SESSION['WEB_AGENT_EXTEN']);
		unset($_SESSION['WEB_AGENT_USERLEVEL']);
		unset($_SESSION['WEB_AGENT_USERID']);
		unset($_SESSION['WEB_AGENT_PWD']);
		unset($_SESSION['WEB_AGENT_TENANTID']);
	}

    $loginResponse = NULL;
	
	if ( isset( $_POST['btnloginclicked'] ) && $_POST['btnloginclicked'] != '') {
	
		
	
		// validation for login should be here
		$socket = new ClientSocket(SERVER_IP,SERVER_PORT);

		if ( $socket->connect() ) {
			$serverResponse = new ServerResponse($socket->sendMessage('<login><uid>' . $_POST['txtusername'] . '</uid><pwd>' .
				$_POST['txtpassword'] . '</pwd><ext>' . $_POST['txtextension'] . 
				'</ext><app>webagent</app></login>'));
			
			$loginResponse = $serverResponse->getResponseObject();
/* var_dump($loginResponse->getResponseCode());
exit; */
			if ( $loginResponse->getResponseCode() >=2 && $loginResponse->getResponseCode() <= 4 ) {

				$_SESSION['WEB_AGENT_SESSION'] = $loginResponse->getSessionID();
				$_SESSION['WEB_AGENT_FNAME'] = $loginResponse->getFirstName();
				$_SESSION['WEB_AGENT_LNAME'] = $loginResponse->getLastName();
				$_SESSION['WEB_AGENT_TENANT'] = $loginResponse->getTenant();
				$_SESSION['WEB_AGENT_TENANTID'] = $loginResponse->getTenantID();
				$_SESSION['WEB_AGENT_USER'] = $_POST['txtusername'];
				$_SESSION['WEB_AGENT_EXTEN'] = $_POST['txtextension'];
				$_SESSION['WEB_AGENT_USERLEVEL'] = $loginResponse->getResponseCode();
				$_SESSION['WEB_AGENT_USERID'] = $loginResponse->getUserID();
				$_SESSION['WEB_AGENT_PWD'] = $_POST['txtpassword'];

				header('Location: agent.php');
			}
		} else {
			$socketError = "Unable to connect to the Accordia CIS server. Please contact your administrator!!!";
			//throw new Exception("Unable to connect to the server!!!!");
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Accordia Solution - Agent Login</title>
<link href="css/admin.css" type="text/css" rel="stylesheet" />
<LINK REL=StyleSheet HREF="css/dimming.css" TYPE="text/css" />
<SCRIPT LANGUAGE="JavaScript" SRC="javascript/dimmingdiv.js">
</SCRIPT>
<script language="javascript">
		    function displayWindow()
		    {
		        var w, h, l, t;
		        w = 400;
		        h = 200;
		        l = screen.width/4;
		        t = screen.height/4;
                
                // no title		        
		        // displayFloatingDiv('windowcontent', '', w, h, l, t);

                // with title		        
		        displayFloatingDiv('windowcontent', 'Floating and Dimming Div', w, h, l, t);
		    }
		</script>
<STYLE TYPE="text/css"> 
<!-- 
body {overflow: hidden}; 
--> 
</STYLE>  
</head>
<body>
<table width="100%" height="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" bgcolor="#FFFFFF"><p>&nbsp;</p>
      <p><img src="images/login.gif" width="296" height="26" /></p>
    <p><img src="images/btn_admin.jpg" width="263" height="68" /></p></td>
  </tr>
  <tr>
    <td height="382" align="center" background="images/tile_login.gif"><table width="886" height="382" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="bottom" background="images/backg_login.jpg"><table width="660" height="328" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="23" height="328" background="images/box_corner_1.png">&nbsp;</td>
            <td width="243" height="328" background="images/box_corner_2.png">&nbsp;</td>
            <td width="366" height="328" align="center" background="images/box_corner_3.png"><br />
              <table width="350" height="270" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center" valign="top"><p><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><br />
                  Welcome to</font><font color="#FFFFFF" size="4" face="Arial, Helvetica, sans-serif"><br />
                    ACCORDIA CALL CENTER<br />
                    </font><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><br />
                      Please enter your username, password &amp; extension number:</font></p>
                  <form id="frmLogin" name="frmLogin" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                  <table width="286" border="0" cellspacing="2" cellpadding="0">
                    <tr>
                      <td width="85"><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Username</font></td>
                      <td width="195">
                          <input type="text" name="txtusername" id="txtusername" value="<?php if ( isset($_POST['txtusername']) ) { echo $_POST['txtusername']; } ?>" required />
                      </td>
                    </tr>
                    <tr>
                      <td><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Password</font></td>
                      <td>
                      		<input type="password" name="txtpassword" id="txtpassword" value="<?php if ( isset($_POST['txtpassword']) ) { echo $_POST['txtpassword']; } ?>" required />
                          </label>
                      </td>
                    </tr>
                    <tr>
                      <td><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Extension No.</font></td>
                      <td>
                          <input type="text" name="txtextension" id="txtextension" value="<?php if ( isset($_POST['txtextension']) ) { echo $_POST['txtextension']; } ?>" oninvalid="this.setCustomValidity('Please enter extension number')" oninput="setCustomValidity('')"  required />
                       </td>
                    </tr>
                    <tr>
                      <td height="45">&nbsp;</td>
                      <td><!--<a href="#"><img src="images/btn_login.gif" width="107" height="33" border="0" /></a>-->
                      	<input type="hidden" name="btnloginclicked" id="btnloginclicked" value="" />
                      	<input type="image" name="btnlogin" id="btnlogin" src="images/btn_login.gif" alt="Login" value="Login" onclick="this.form.btnloginclicked.value = this.value" />
                        <!-- <input type="submit" id="btnlogin" name="btnlogin" value="Login"  />  -->
                      </td>
                    </tr>
                    <tr>
                    	<td colspan="2" align="center">
<?php
				echo '<b><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">';
				if ( $loginResponse != NULL ) {
					
					switch ( $loginResponse->getResponseCode() ) {
						case  -1: /* invalid username and password */
							echo 'Invalid username or password!!!';
							break;
						case -2: /* invalid extension */
						case -3: /* extension does not belong to this tenant */
							echo 'Invalid SIP extensions!!!';
							break;
					    case -4: /* use by other user */
							echo 'SIP extension is being used by other user!!!';
							break;
						case 0: /* user has been disable */
							echo 'Your username has been disabled!!!';
							break;
						case 1: /* Guest */
							echo 'Insufficient privilege!!!';
							break;
					}
				} else if ( $socketError != NULL ) {
					echo $socketError;
				} else {
					echo '&nbsp;';
				}
				echo '</font></b>';
?>
                        </td>
                    </tr>
                  </table>   

                  </form>
                  <p><font color="#FFFFFF" size="4" face="Arial, Helvetica, sans-serif"><br />
                  </font></p></td>
              </tr>
            </table></td>
            <td width="18" height="328" background="images/box_corner_4.png">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
</table>

<div style="width: 518px; height: 182px;visibility:hidden" id="windowcontent">
            <script language="javascript">
            function Hello()
            {
               alert('Hello '+  document.getElementById('yourname').value + '!');
               
               
            }
            </script>
            <table >
            <tr><td colspan="2"></td></tr>
            <tr>
            <td>Your name:</td>
            <td><input type="text" style="width: 292px" id="yourname" /></td>
            </tr>
            <tr>
            <td colspan="2">
            <input type="button" value="Hello button" onClick="Hello();" style="width: 119px"/></td>
            </tr>
            <tr><td colspan="2"><br />Click the left mouse button on the blue header then move the floating div!!!</td></tr>
            </table>
</div>
</body>
</html>