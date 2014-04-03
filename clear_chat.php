<?php 

####### db config ##########
$db_username = 'chat';
$db_password = 'ch@t';
$db_name = 'chat';
$db_host = 'localhost';
####### db config end ##########

//connect to mysql db
$sql_con = mysqli_connect($db_host, $db_username, $db_password,$db_name)or die('could not connect to database');

mysqli_query($sql_con,"TRUNCATE TABLE shout_box")
?>