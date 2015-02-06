//Fine Uploader JS
  $(document).ready(function () {

    $('#fine-uploader').fineUploader({
      request: {
        endpoint: 'chatroom/upload_files/'
      },
      template: 'chatbox-template',
      multiple: false,
      validation: {
        allowedExtensions: ['jpeg', 'jpg', 'gif', 'png', 'txt'],
        //sizeLimit: 51200  // 50 kB = 50 * 1024 bytes
      },
      callbacks: {
        onComplete: function(id, fileName, responseJSON) {
           console.log(responseJSON);
            if (responseJSON.status == 'success') {
                $('.error').hide();
                //$(".qq-upload-status-text").text("File Uploaded Successfully.");
                $(".qq-upload-list").hide();
                
                //append li 
                $('#chatroomscroll').append(responseJSON.page);

                //scroll to the bottom
              
            }
            else if(responseJSON.status == 'false')
            {
                $('.qq-upload-status-text').html(responseJSON.error);
            }
        }
      }
    }).on('submit', function (event, id, filename) {
            $(this).fineUploader('setParams', { 'user_id': $('.current_active_user').attr('id')});
        });

  });


    //for tabs
    $(function() {
        $( "#tabs" ).tabs();
    });


	//on change of chat user
	$('.chat_user').click(function(e) {
		var user_id = $(this).attr('id');
		
		$(".online-list li").removeClass("current_active_user");
		$(this).addClass('current_active_user');

		updateHeader();
		autoChatUpdate();
    });

    function updateHeader()
    {
    	var user_id = $('.current_active_user').attr('id');

    	$.ajax({
	        url: "chatroom/userchat/get_header/"+user_id,
	        dataType: 'json',
	        success: function(response) {

	            if (response.status == 'true')
	            {
			    	var header = $('#chat_header').html();
			    	var hrendered = Mustache.render(header, response);
			        $(".header-title-sec").html(hrendered);
	            }

	        }
	    });

    }

	var autoChatUpdate = function()
	{
        var id = $('.current_active_user').attr('id');
	    $.ajax({
	        url: "chatroom/userchat/index/"+id,
	        dataType: 'json',
	        success: function(response) {

	            if (response.status == 'true')
	            {
	                //var template = $("#user_chat_history").html();
	                //var rendered = Mustache.render(template, response);
	            	
	            	var rendered = response.page;
	                $("#chatroomscroll").html(rendered);

	                // $("#chatroomscroll").scrollTop = $("#chatroomscroll").animate({scrollTop: 3000});
	                // setTimeout(autoChatUpdate, 3000);
	            }

	        }
	    });

	}

	$(function(){
	    //if no user is selected
        var user_id = $('.current_active_user').attr('id');
	  	if(!user_id)
	  	{
	  		$('.online-list li:first').addClass('current_active_user');
	  	}

        autoChatUpdate();

	  	updateHeader();

	  	$("#chatroomscroll").scrollTop = $("#chatroomscroll").animate({scrollTop: 10000});
	});

	$(document).on('keyup', '#fc_chat_mail', function(e) {
	    e.preventDefault();
	    if (e.keyCode == 13) {
	      send_message();
	    }
	});

	$('#send_chats').click(function(e) {
		alert('ok');
		//send_message();
    });

    function send_message() {
    	var user_id = $('.current_active_user').attr('id');

    	var message = $("#fc_chat_mail").val();
        var finalUrl = site_url + 'chatroom/userchat/save_message/'+user_id;
        $.ajax({
            url: finalUrl,
            dataType: 'JSON',
            type: 'POST',
            data: { "chat_message": message},
            async: false,
            success: function(response) {
                if (response.status == 'true')
                {
                    var template = $("#user_chat_history").html();
                    Mustache.parse(template);
                    var rendered = Mustache.render(template, {date: 'Just now', message: response.chat_messsage.message, send_from_me: true});

                    $('#chatroomscroll').append(rendered);
                    $('#fc_chat_mail').val('');
                }
                else
                    $('#chatroomscroll').append(response.error_msg);

                $("#chatroomscroll").scrollTop = $("#chatroomscroll").animate({scrollTop: 10000});
            }
        })
    }