
<script language="javascript" type="text/javascript">

jQuery('.accordion dt').click(function() {
	jQuery(this).toggleClass('active').find('i').toggleClass('fa-plus fa-minus')
		   .closest('dt').siblings('dt')
		   .removeClass('active').find('i').removeClass('fa-minus').addClass('fa-plus');

		  jQuery(this).next('.accordion_content').slideToggle().siblings('.accordion_content').slideUp();


});

jQuery('.accordion_content').hide();

// chat start here //
// jquery start here
jQuery(function ($) {

	//$('.shout_box').css('display', 'block');

	// load messages every 1000 milliseconds from server.
	load_data = {'fetch':1};
	window.setInterval(function(){
	 $.post('chat/shout.php', load_data,  function(data) {
		var obj = jQuery.parseJSON(data);
		//alert(obj);
		var window1_userid = $("#userid1").val().trim();
		var window2_userid = $("#userid2").val().trim();
		var window3_userid = $("#userid3").val().trim();
		//console.log("1" + window1_userid + "2" + window2_userid + "3" + window3_userid);
		var message1 = "";
		var message2 = "";
		var message3 = "";

		for(var i in obj){
			//alert(obj[i].message);
			var display = '<div class="shout_msg"><time>'+obj[i].date_time+'</time><span class="username">'+obj[i].user+'</span> <span class="message">'+obj[i].message+'</span></div>';
			if(obj[i].from_user == window1_userid || obj[i].to_user == window1_userid){
				message1 += display;
				//alert(display);
			}else if(obj[i].from_user == window2_userid  || obj[i].to_user == window2_userid){
				message2 += display;
			}else if(obj[i].from_user == window3_userid  || obj[i].to_user == window3_userid){
				message3 += display;
			}else if(obj[i].received == 0){
				if(window1_userid == 0){
						window1_userid = obj[i].from_user;
						message1 += display;
						$("#name1").text(obj[i].user);
						$("#userid1").val(obj[i].from_user);
						$('#window1').css('display', 'block');
				}else if(window2_userid == 0){
						window2_userid = obj[i].from_user;
						message2 += display;
						$("#name2").text(obj[i].user);
						$("#userid2").val(obj[i].from_user);
						$('#window2').css('display', 'block');
				}else if(window3_userid == 0){
						window3_userid = obj[i].from_user;
						message3 += display;
						$("#name3").text(obj[i].user);
						$("#userid3").val(obj[i].from_user);
						$('#window3').css('display', 'block');
				}
			}

			if(obj[i].to_user == <?php echo $_SESSION['WEB_ADMIN_USERID']; ?>){

				var status = 1;
				update_received(obj[i].id,status);
				//alert("update received");
			}
		}

		$('div#message_box1').html(message1);
		$('div#message_box2').html(message2);
		$('div#message_box3').html(message3);

		//$('.message_box').html(data);
		var scrolltoh1 = $('#message_box1')[0].scrollHeight;
		$('#message_box1').scrollTop(scrolltoh1);

		var scrolltoh2 = $('#message_box2')[0].scrollHeight;
		$('#message_box2').scrollTop(scrolltoh2);

		var scrolltoh3 = $('#message_box3')[0].scrollHeight;
		$('#message_box3').scrollTop(scrolltoh3);

	 });
	}, 1000);

	// close chat
	$(".close_chat").click(function (event) {
		event.preventDefault();
		//alert($(this).parent().parent().parent().attr("id"));
		var status = 2;


		update_received(<?php echo $_SESSION['WEB_ADMIN_USERID']; ?>,status);
		var window_id = $(this).parent().parent().parent().attr("id");
		if(window_id == "window1"){
			$('#window1').css('display', 'none');
			$("#userid1").val(0);

		}else if(window_id == "window2"){
			$('#window2').css('display', 'none');
			$("#userid2").val(0);

		}else if(window_id == "window3"){
			$('#window3').css('display', 'none');
			$("#userid3").val(0);

		}

	});

	function update_received(id,status)
	{
		//alert(id);
		$.ajax({
			type: "POST",
			url: 'chat/update_flag.php',
			data : {
				id : id,
				status : status,
			},
			success : function(data) {

				//$('.shout_box').css('display', 'block');

			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				//alert(XMLHttpRequest + " : " + textStatus + " : " + errorThrown);
			}
		});
	}

	// open chat
	$(".open_chat").click(function (event) {
		event.preventDefault();
		var userid =  $(this).attr('data-id');
		var name =  $(this).attr('data-name');
		var fetch =  1;
		//$('.shout_username').val(name);
		//('.userid').val(userid);
		//$("#name1").text(name);

		var window_num = 0;

		if ( $("#userid1").val() == userid )
		{
			window_num = 1;
		}
		else if ( $("#userid2").val() == userid )
		{
			window_num = 2;
		}
		else if ( $("#userid3").val() == userid )
		{
			window_num = 3;
		}

		if ( window_num == 0 )
		{
			if ( $("#userid1").val() == 0 )
			{
				window_num = 1;
			}
			else if ( $("#userid2").val() == 0 )
			{
				window_num = 2;
			}
			else if ( $("#userid3").val() == 0 )
			{
				window_num = 3;
			}
		}
		if(window_num == 0){
			alert("Maximum only 3, close 1 chat window for another new chat");
		}else{
			$("#name" + window_num).text(name);
			$("#userid" + window_num).val(userid.trim());
			//alert("("+userid.trim()+")");
			$("#window" + window_num).css('display', 'block');
		}
		//if ( $(#userid1").val == 0 )
		//get_user_data(userid,fetch,window_num);
	});

	//method to trigger when user hits enter key
	$(".shout_message").keypress(function(evt) {
		if(evt.which == 13) {

				var shout_message_id = "0";

				var shout_message = $(this).attr("id");
				if(shout_message == "shout_message1"){
					shout_message_id = "1";
				}else if(shout_message == "shout_message2"){
					shout_message_id = "2";
				}else if(shout_message == "shout_message3"){
					shout_message_id = "3";
				}

				var iusername = $('#shout_username'+shout_message_id).val();
				var imessage = $('#shout_message'+shout_message_id).val();
				var userid = $('#userid'+shout_message_id).val();
				var from_user = $('#from_user'+shout_message_id).val();
				post_data = {'username':iusername, 'message':imessage, 'from_user':from_user,'userid':userid};

				//send data to "shout.php" using jQuery $.post()
				$.post('chat/shout.php', post_data, function(data) {

					//append data into messagebox with jQuery fade effect!
					$(data).hide().appendTo('#message_box'+shout_message_id).fadeIn();

					//keep scrolled to bottom of chat!
					var scrolltoh = $('#message_box'+shout_message_id)[0].scrollHeight;
					$('#message_box'+shout_message_id).scrollTop(scrolltoh);

					//reset value of message box
					$('#shout_message'+shout_message_id).val('');

				}).fail(function(err) {

				//alert HTTP server error
				//alert(err.statusText);
				});
		}
	});

	//toggle hide/show shout box
	$(".close_btn").click(function (e) {

		var toggle_object = "";

		var window_id = $(this).parent().attr("id");
		if(window_id == "window1"){
			toggle_object = "#toggle1";
		}else if(window_id == "window2"){
			toggle_object = "#toggle2";
		}else if(window_id == "window3"){
			toggle_object = "#toggle3";
		}

		//get CSS display state of .toggle_chat element
		var toggleState = $(toggle_object).css('display');

		//toggle show/hide chat box
		$(toggle_object).slideToggle();

		//use toggleState var to change close/open icon image
		if(toggleState == 'block')
		{
			$(".header div").attr('class', 'open_btn');
		}else{
			$(".header div").attr('class', 'close_btn');
		}
	});

		//toggle hide/show shout box
	$(".show").click(function (e) {
		//get CSS display state of .toggle_chat element
		var toggleState = $('.toggle_chat2').css('display');

		//toggle show/hide chat box
		$('.toggle_chat2').slideToggle();

		//use toggleState var to change close/open icon image
		if(toggleState == 'block')
		{
			$(".user_header div").attr('class', 'open_btn');
		}else{
			$(".user_header div").attr('class', 'close_btn');
		}
	});
});
// chat end here //

</script>