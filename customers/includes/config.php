<?php
$hostname_contacts = "localhost";  
$database_contacts = "simplecustomer"; //The name of the database
$username_contacts = "callcenter"; //The username for the database
$password_contacts = "ca11c3nt3r"; // The password for the database
$contacts = mysql_connect($hostname_contacts, $username_contacts, $password_contacts) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_select_db($database_contacts, $contacts);

?>
