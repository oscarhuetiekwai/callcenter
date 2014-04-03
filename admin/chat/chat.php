 <!-- start chat -->
<div id="window3" class="shout_box" style="right: 45%;display:none;">
<div class="header close_btn "><span id="name3" class="name"></span> <span style="float:right;" class=""><a class="close_chat">&times;</a></span></div>
  <div class="toggle_chat " id="toggle3">
  <div class="message_box" id="message_box3">
    </div>
    <div class="user_info">
    <input name="shout_username" id="shout_username3"  class="shout_username" value="<?php echo $_SESSION['WEB_ADMIN_USER']; ?>" type="hidden" placeholder="Your Name"  />
	<input name="userid" id="userid3" class="userid" value="0" type="hidden" />
	<input name="from_user" id="from_user3" class="from_user" type="hidden" value="<?php echo trim($_SESSION['WEB_ADMIN_USERID']); ?>" />
	<input name="shout_message" id="shout_message3" class="shout_message" type="text" placeholder="Type Message Hit Enter" maxlength="500" />
    </div>
    </div>
</div>

<div id="window2" class="shout_box" style="right: 29%;display:none;">
<div class="header close_btn "><span id="name2" class="name"></span> <span style="float:right;" class=""><a class="close_chat">&times;</a></span></div>
  <div class="toggle_chat " id="toggle2">
  <div class="message_box" id="message_box2">
    </div>
    <div class="user_info">
    <input name="shout_username" id="shout_username2"  class="shout_username" value="<?php echo $_SESSION['WEB_ADMIN_USER']; ?>" type="hidden" placeholder="Your Name"  />
	<input name="userid" id="userid2" class="userid" value="0" type="hidden" />
	<input name="from_user" id="from_user2" class="from_user" type="hidden" value="<?php echo  trim($_SESSION['WEB_ADMIN_USERID']); ?>" />
	<input name="shout_message" id="shout_message2"  class="shout_message"  type="text" placeholder="Type Message Hit Enter" maxlength="500" />
    </div>
    </div>
</div>

<div id="window1" class="shout_box" style="right: 13%;display:none;">
<div class="header close_btn "><span id="name1" class="name"></span> <span style="float:right;" class=""><a class="close_chat">&times;</a></span></div>
  <div class="toggle_chat " id="toggle1">
  <div class="message_box" id="message_box1">
    </div>
    <div class="user_info">
    <input name="shout_username" id="shout_username1"  class="shout_username" value="<?php echo $_SESSION['WEB_ADMIN_USER']; ?>" type="hidden" placeholder="Your Name"  />
	<input name="userid" id="userid1" class="userid" value="0"  type="hidden" />
	<input name="from_user" id="from_user1" class="from_user" type="hidden" value="<?php echo trim($_SESSION['WEB_ADMIN_USERID']); ?>" />
	<input name="shout_message" id="shout_message1" class="shout_message"  type="text" placeholder="Type Message Hit Enter" maxlength="500" />
    </div>
    </div>
</div>

<div class="user">
<div class="header show">User List <span style="float:right;" class="">&nbsp;-</span></div>
<div class="toggle_chat2 " style="overflow-y:scroll;overflow-x:hidden;height:auto;max-height:400px;display: none;">
		<?php include("chat/user.php"); ?>
		<dl class="accordion">
		<dt><span>Supervisor</span></dt>
			<dd class="accordion_content">
			<?php
				while($supervisor_row = mysql_fetch_array($get_supervisor)){
			?>
				<a id="user_list"  class="open_chat" data-id="<?php echo $supervisor_row["userid"]; ?>" data-name="<?php echo $supervisor_row["username"]; ?>"><?php echo substr($supervisor_row["username"],0,13); ?></a><br />
			<?php } ?>
			</dd>
		<dt><span>Agent</span></dt>
			<dd class="accordion_content">
			<?php
				while($user_row = mysql_fetch_array($get_user)){
			?>
				<a id="user_list"  class="open_chat" data-id="<?php echo $user_row["userid"]; ?>" data-name="<?php echo $user_row["username"]; ?>"><?php echo substr($user_row["username"],0,13); ?></a><br />
			<?php } ?>
			</dd>
		</dl>
    </div>
</div>