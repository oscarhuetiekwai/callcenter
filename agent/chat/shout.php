<?php
session_start();


####### db config ##########
$db_username = 'chat';
$db_password = 'ch@t';
$db_name = 'chat';
$db_host = 'localhost';
####### db config end ##########


if($_POST)
{

	//connect to mysql db
	$sql_con = mysqli_connect($db_host, $db_username, $db_password,$db_name)or die('could not connect to database');


	//check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        die();
    }

	if(isset($_POST["message"]) &&  strlen($_POST["message"])>0)
	{
		//sanitize user name and message received from chat box
		//You can replace username with registerd username, if only registered users are allowed.
		$username = filter_var(trim($_POST["username"]),FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
		$message = filter_var(trim($_POST["message"]),FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
		$to_user = $_POST["userid"];
		$from_user = $_POST["from_user"];

		$user_ip = $_SERVER['REMOTE_ADDR'];

		$msg_time = date('h:i',time());
		//insert new message in db
		if(mysqli_query($sql_con,"INSERT INTO shout_box(user,from_user,to_user, message,date_time, ip_address) value('$username','$from_user','$to_user','$message','$msg_time','$user_ip')"))
		{
			 // current time
			echo '<div class="shout_msg"><time>'.$msg_time.'</time><span class="username">'.$username.'</span><span class="message">'.$message.'</span></div>';
		}

		// delete all records except last 10, if you don't want to grow your db size!
		//mysqli_query($sql_con,"DELETE FROM shout_box WHERE id NOT IN (SELECT * FROM (SELECT id FROM shout_box ORDER BY id DESC LIMIT 0, 10) as sb)");
	}
	elseif($_POST["fetch"]==1)
	{
	
		## 1st window query ##
/* 		$results1 = mysqli_query($sql_con,"SELECT user, message, date_time FROM  shout_box where from_user = ".$_SESSION['to_user1']." or from_user = 1274 and to_user = ".$_SESSION['to_user1']." ORDER BY shout_box.date_time ASC");
		while($row1 = mysqli_fetch_array($results1))
		{
			$msg_time1 = date('h:i A M d',strtotime($row1["date_time"])); //message posted time
			echo '<div class="shout_msg"><time>'.$msg_time1.'</time><span class="username">'.$row1["user"].'</span> <span class="message">'.$row1["message"].' - '.$_SESSION['to_user1'].'</span></div>';
		}
		
		## 2nd window query ##
		$results2 = mysqli_query($sql_con,"SELECT user, message, date_time FROM  shout_box where from_user = ".$_SESSION['to_user2']." or from_user = 1274 and to_user = ".$_SESSION['to_user2']." ORDER BY shout_box.date_time ASC");
		while($row2 = mysqli_fetch_array($results2))
		{
			$msg_time2 = date('h:i A M d',strtotime($row2["date_time"])); //message posted time
			echo '<div class="shout_msg"><time>'.$msg_time2.'</time><span class="username">'.$row2["user"].'</span> <span class="message">'.$row2["message"].' - '.$_SESSION['to_user2'].'</span></div>';
		}
		
		## 3rd window query ##
		$results3 = mysqli_query($sql_con,"SELECT user, message, date_time FROM  shout_box where from_user = ".$_SESSION['to_user3']." or from_user = 1274 and to_user = ".$_SESSION['to_user3']." ORDER BY shout_box.date_time ASC");
		while($row3 = mysqli_fetch_array($results3))
		{
			$msg_time3 = date('h:i A M d',strtotime($row3["date_time"])); //message posted time
			echo '<div class="shout_msg"><time>'.$msg_time3.'</time><span class="username">'.$row3["user"].'</span> <span class="message">'.$row3["message"].' - '.$_SESSION['to_user3'].'</span></div>';
		} */
		
		$results = mysqli_query($sql_con,"SELECT * FROM  shout_box where from_user = '".trim($_SESSION['WEB_AGENT_USERID'])."' or to_user = '".trim($_SESSION['WEB_AGENT_USERID'])."'  ORDER BY id ASC");
		$array = array();
		while($row = mysqli_fetch_array($results))
		{
			array_push($array, $row);
		} 
		
		echo json_encode($array);
	}	
	else
	{
		header('Do Not too fast');
    	exit();
	}
}