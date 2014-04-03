<?php 
/* 	$db_username2 = 'callcenterb3';
	$db_password2 = 'callcenterb3';
	$db_name2 = 'callcenter';
	$db_host2 = 'localhost';
	 */
	mysql_connect("localhost", "callcenter", "ca11c3nt3r") or
    die("Could not connect: " . mysql_error());
	mysql_select_db("callcenter");
	
	//$sql_con2 = mysql_connect($db_host2, $db_username2, $db_password2,$db_name2)or die('could not connect to database');
	
	$get_user = mysql_query("SELECT users.username,users.lastname,users.userid FROM users where users.userlevel = 3 and users.userdbstatus = 'A' and users.tenantid = '".$_SESSION['WEB_AGENT_TENANTID']."' order by username ASC ");
	
	//var_dump("SELECT users.username,users.lastname,users.userid FROM users where users.userlevel = 3 and users.userdbstatus = 'A' and users.tenantid = '".$_SESSION['WEB_AGENT_TENANTID']."' order by username ASC ");

	//$query_team = "SELECT teams.name,teams.teamid FROM teams where teams.tenantid = '1'  order by teams.name ASC";
	//$get_team = mysql_query($query_team);

	

?>