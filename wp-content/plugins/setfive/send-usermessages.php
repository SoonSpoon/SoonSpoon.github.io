<?php
    /*
     * Script sends SMS message with list of available reservations to users, to be executed by a cron job. 
     */
 
	global $wpdb, $table_prefix;
	
	if(!isset($wpdb))
	{
		require_once('../../../wp-config.php');
		require_once('../../../wp-includes/wp-db.php');
	}

    // Include the Twilio-PHP library 
    require "twilio-php-latest/Services/Twilio.php";
 
    /*
     * Grab all the Twilio settings option field values neeeded to send the message
     */
    
    // set our AccountSid and AuthToken saved in the Twilio User settings options screen
    
    $AccountSid = get_option( 'twilio_sid');
    $AuthToken = get_option( 'twilio_auth_token');
    
    $sent_from_numbers = get_option( 'twilio_user_from_phones');
    
    $sent_from_number_list = explode(',',$sent_from_numbers);
    
    $sent_from_count = count($sent_from_number_list);
    
    $send_text_prefix = get_option( 'twilio_user_message_prefix');
    
    $user_csv_list = get_option( 'twilio_user_csv_list');
    
    // instantiate a new Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);

    $exceptionList = array();
    $sms = array();
    
    //create array from csv
    $user_csv_array = parse_csv($user_csv_list);
    
    $sent_message_count = 0;

	    foreach ($user_csv_array as $user) {
	    
	    	$user_number = $user[0];
	    		    	
	    	try {
		    	
	    		//get sent from counter in db
	    		$sql = "SELECT twilio_setting_value
		    			FROM uma_twilio_settings t
		    			WHERE t.twilio_setting_id = 7";
	    			
	    		$count_record = $wpdb->get_results( $sql );
	    		$count = intval($count_record[0]->twilio_setting_value);
	    				    			
	    		$sent_from_number = $sent_from_number_list[$count++ % $sent_from_count];
		    	
		    	//send the message!
 		    	$sms[] = $client->account->messages->sendMessage(
		    
		    			// randomized from number
		    			$sent_from_number,
		    
		    			// the number we are sending to - Any phone number
		    			$user_number,
		    
		    			// the sms body
		    			$send_text_prefix . $reservation_string
		    	); 
		    	
 		    	//update sent from counter in db
 		    	$sql = "UPDATE uma_twilio_settings t
 		    	SET t.twilio_setting_value = $count
 		    	WHERE t.twilio_setting_id = 7";
 		    	 
 		    	$processed = $wpdb->get_results( $sql );
 		    	
		    	$sent_message_count++;
	    	} catch (Exception $e) {
	    		$exceptionList[] = $e->getMessage();
	    	}

	    } 

	if(!empty($exceptionList)){
		echo "<div style='display:none;color:red' id='confirmation-not-sent'><p><strong>The following exceptions were encountered, these numbers did not receive a text: </strong></p><ul>";
		
		foreach($exceptionList as $item){
			echo "<li>$item</li>";
		}
		echo "</ul></div><br/>";
	}
	   
    // Display a confirmation message on the screen
	   
	echo "<p style='display:none;color:green' id='confirmation-sent'> <strong>Sent $sent_message_count messages successfully. </strong></p><br/><br/>";
	
    // helper function returns a two-dimensional array or rows and fields
    
    function parse_csv ($csv_string, $delimiter = ",", $skip_empty_lines = true, $trim_fields = true)
    {
    	$enc = preg_replace('/(?<!")""/', '!!Q!!', $csv_string);
    	$enc = preg_replace_callback(
    			'/"(.*?)"/s',
    			function ($field) {
    				return urlencode(utf8_encode($field[1]));
    			},
    			$enc
    	);
    	$lines = preg_split($skip_empty_lines ? ($trim_fields ? '/( *\R)+/s' : '/\R+/s') : '/\R/s', $enc);
    	return array_map(
    			function ($line) use ($delimiter, $trim_fields) {
    				$fields = $trim_fields ? array_map('trim', explode($delimiter, $line)) : explode($delimiter, $line);
    				return array_map(
    						function ($field) {
    							return str_replace('!!Q!!', '"', utf8_decode(urldecode($field)));
    						},
    						$fields
    				);
    			},
    			$lines
    	);
    }