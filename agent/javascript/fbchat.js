jQuery(function ($) {		


	// close chat
	$(".close_chat").click(function (event) {
		event.preventDefault();
		//alert($(this).parent().parent().parent().attr("id"));

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
			$("#userid" + window_num).val(userid);
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
				$.post('shout.php', post_data, function(data) {

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