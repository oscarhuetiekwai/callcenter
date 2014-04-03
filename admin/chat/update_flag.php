<?php 
	$db_username2 = 'chat';
	$db_password2 = 'ch@t';
	$db_name2 = 'chat';
	$db_host2 = 'localhost';
	$sql_con2 = mysqli_connect($db_host2, $db_username2, $db_password2,$db_name2)or die('could not connect to database');
	
	
	$id = $_POST["id"];
	$status = $_POST["status"];
	
	if($status == 1){
		$get_user = mysqli_query($sql_con2,"update shout_box set received = 1 where id = ".$id);
	}else if($status == 2){
		$get_user = mysqli_query($sql_con2,"update shout_box set received = 1 where from_user = ".trim($id));
	}
	
	//error_log("update shout_box set received = 1 where id = ".$id);
?>