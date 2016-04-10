<?php
    /*
     * Script sends SMS messages with newly booked reservations to restaurants, to be executed by a cron job. 
     */
 
	global $wpdb, $table_prefix;
	
	if(!isset($wpdb))
	{
		require_once (dirname(__FILE__).'/../../../wp-config.php');
		require_once (dirname(__FILE__).'/../../../wp-includes/wp-db.php');
	}

    // Include the Twilio-PHP library 
    require dirname(__FILE__)."/twilio-php-latest/Services/Twilio.php";
 
    /*
     * Grab all the Twilio settings option field values neeeded to send the message
    */
    
    // set our AccountSid and AuthToken from www.twilio.com/user/account
    $AccountSid = get_option( 'twilio_sid');
    $AuthToken = get_option( 'twilio_auth_token');
    
    $sent_from_number = get_option( 'twilio_restaurant_from_phone');
    $sent_from_number_list = explode(',',$sent_from_number);
    $sent_from_count = count($sent_from_number_list);
    
    $send_text_prefix = get_option( 'twilio_restaurant_message_prefix');
 
    // instantiate a new Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
 
    // find all restaurant users (aka those with twilio phone numbers set)
    
    $sql = 'select * from uma_usermeta where uma_usermeta.meta_key = "twilio_phone"';
    
  	$restaurants = $wpdb->get_results( $sql );
  	
    // Loop over all our users and send SMS messages to phone numbers listed on file if there's unprocessed booked reservations.

  	$ESTTZ = new DateTimeZone('America/New_York');
  	$today = new DateTime("now",$ESTTZ);
  	$current_time_string = $today->format('Y-m-d H:i');
  	echo "run time: $current_time_string"."\r\n";
  	
    foreach ($restaurants as $restaurant) {
 			
    	$user_id = $restaurant->user_id;

    	//create list of phone numbers from the csv list in the restaurant's user profile
    	$numbers = explode(',',$restaurant->meta_value);

		//check to see if the current time falls within the business hours of the restauraunt		
    	$today = new DateTime("now",$ESTTZ);
    	$day = $today->format('l');
		$today_hour_minute = intval($today->format('Hi'));
		$day = substr($day,0,3);  
    	
		//pull the opening and closing hours for the current day from the restaurant's user profile
    	$sql = "select * from uma_usermeta where uma_usermeta.user_id = $user_id and uma_usermeta.meta_key like '%$day%' and uma_usermeta.meta_key like'%to%'" ;
    	$to_hours = $wpdb->get_results( $sql );
    	
    	//dont process restaurant if they dont have hours properly set
    	if(count($to_hours) == 0)
    		continue;
    	
    	$hour_minute_to  = intval(str_replace(':','',$to_hours[0]->meta_value));
    	$sql = "select * from uma_usermeta where uma_usermeta.user_id = $user_id and uma_usermeta.meta_key like '%$day%' and uma_usermeta.meta_key like '%from%'";
    	
        $from_hours = $wpdb->get_results( $sql );
		
    	//dont process restaurant if they dont have from hours properly set
    	if(count($from_hours) == 0)
    		continue;
    	
    	$hour_minute_from  = intval(str_replace(':','',$from_hours[0]->meta_value));
    	
		echo "day: $day user id: $user_id today hour: $today_hour_minute hou from: $hour_minute_from hour_to: $hour_minute_to"."\r\n";
   	
    	//only send out messages if it's during business hours
    	if ($today_hour_minute >= $hour_minute_from && $today_hour_minute <= $hour_minute_to):
    	
    		//
	    	$sql = "SELECT *
    			FROM uma_posts p
    			INNER JOIN uma_postmeta m
    			ON p.ID = m.post_id
    			WHERE p.post_type = 'reservation'
    			AND p.post_status = 'booked'
	    		AND p.post_author = $user_id
    			AND (m.meta_key = 'is_processed_restaurant' AND m.meta_value = '0' )";
	    	
	    	$reservations = $wpdb->get_results( $sql );
	    	
	    	foreach ($reservations as $reservation) {	    		
	    		/*
	    		 * Grab the field data from the reservation post, use get_post_meta function to grab custom fields added
	    		*/
	    		$post_title = $reservation->post_title;
	    		$post_id = $reservation->ID;
	    		$diner_name = get_post_meta($post_id, 'diner_name',true);
	    		$diner_email = get_post_meta($post_id, 'diner_phone',true);
	    		$diner_phone = get_post_meta($post_id, 'diner_email',true);
				$reservation_notes = get_post_meta($post_id, 'reservation_notes',true);


	    		/* If you were to add promo code field it'd be here..first you'd add the custom field using the wordpress backend ui then save it here.
	    		*  For example,get the promo code custom field, check if its set and then build the string to append onto the sms body of the twilio api call
	    		*  you'd add something like this here:
	    		*  
	    		*  		$promo_code = get_post_meta($post_id, 'promo_code',true);
	    		*		if($promo_code != ''){
	    		*			$promo_code = 'Promo code: $promo_code';
	    		*		}
	    		*
	    		*  and then the commented line on 103 would replace the one on 100
			*  DO NOT FORGET TO GO TO FUNCTIONS.PHP AND UPDATE THE CODE THERE - Conor
	    		*/
	    		
	    				//build out the message you want to send the restaurants
	    		$message = $send_text_prefix . ' ' . $post_title . ' for ' . $diner_name . ', ' . $diner_phone . ', ' . $diner_email . '. Reservation Notes: ' . $reservation_notes . '.';
	    		
	    		//Example with promo_code appended
	    		//$message = $send_text_prefix . ' ' . $post_title . ' for ' . $diner_name . ', ' . $diner_phone . ', ' . $diner_email . '. ' . $promo_code . ' .'
	    		
	    		foreach($numbers as $number){

				   try{
					   	//get sent from counter in db
					   	$sql = "SELECT twilio_setting_value
		    			FROM uma_twilio_settings t
		    			WHERE t.twilio_setting_id = 2";
					   	
					   	$count_record = $wpdb->get_results( $sql );
					   	$count = intval($count_record[0]->twilio_setting_value);
					   	
					   	$sent_from_number = $sent_from_number_list[$count++ % $sent_from_count];
				   	
		    			$sms = $client->account->messages->sendMessage(
		    					 
		    			// set the from number
		    			$sent_from_number,
		   
		    			// the number we are sending to 
		    			$number,
		    			 
		    			// the sms body
		    			$message
		    				
						);
		    			echo "Sent message to $number" . "\n";
	    			
						//update sent from counter in db
		    			$sql = "UPDATE uma_twilio_settings t
		    			SET t.twilio_setting_value = $count
		    			WHERE t.twilio_setting_id = 2";
		    			
		    			$processed = $wpdb->get_results( $sql );
		    			
	    			} catch (Exception $e) {
	    				$error = $e->getMessage();
	    				echo "Message not sent, exception occurred: $error " . "\n";
	    			}
	    			
	    		}
	
	    		//run update statment to mark reservations as processed
	    		$sql = "UPDATE uma_postmeta m
	    		SET m.meta_value = '1'
	    		WHERE m.post_id = $post_id
	    		AND m.meta_key = 'is_processed_restaurant'";
	    		
	    		$processed = $wpdb->get_results( $sql );
	    	}

    	endif;
    }