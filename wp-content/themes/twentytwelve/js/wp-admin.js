jQuery(document).ready(function($) {
	
	$('#wp-admin-bar-new-content').remove();

	//Batch Send User Messages from Twilio Settings page on WP admin backend
	
	$('#batchSendUserMessage').on('click',function(){
			var msg = $('textarea[name="twilio_user_message_prefix"]').val();
			var list = $('textarea[name="twilio_user_csv_list"]').val();
		
			var answer = confirm('Are you sure you want to send the following message? ' + '"' + msg + '"');
			
			if (answer){
				$.post('/wp-admin/admin-ajax.php', { action: "send_usermessages", message: msg, userlist: list }, function(response) {
					$('#sendUserMessageWrapper').prepend(response);

					$('#confirmation-not-sent').fadeIn(400);
					$('#confirmation-sent').fadeIn(400);
					
				});
			}
			return false;
	});
	
});  
