<?php 
	mysql_connect("localhost", "callcenter", "ca11c3nt3r") or
    die("Could not connect: " . mysql_error());
	mysql_select_db("callcenter");
	
	$get_user = mysql_query("SELECT username,lastname,userid FROM users where userlevel = 2 and userdbstatus = 'A' and tenantid = '".$_SESSION['WEB_ADMIN_TENANTID']."'  and users.userstatusid != 0 order by userlevel,username ASC");

	$get_supervisor = mysql_query("SELECT username,lastname,userid FROM users where userlevel = 3 and userdbstatus = 'A' and userid != '".$_SESSION['WEB_ADMIN_USERID']."'  and  tenantid = '".$_SESSION['WEB_ADMIN_TENANTID']."' order by username ASC ");


	if($get_user === FALSE) {
		die(mysql_error()); // TODO: better error handling
	}
	
	if($get_supervisor === FALSE) {
		die(mysql_error()); // TODO: better error handling
	}
	
?>