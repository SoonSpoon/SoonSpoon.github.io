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

    // 1. grab the csv filename from the media library
    
    $url = get_site_url();
    
    $filename = "$url/wp-content/uploads/reservations.csv";
        
    echo "Processing filename " . $filename ."\n";  
        
    print_r($filename); 
        
    // 2. parse csv file into an array
    
    try{

        ini_set("auto_detect_line_endings", true);
        $handle = fopen($filename, "r");

        $lines = array();
        while (($buffer = fgets($handle)) !== false) {
            $lines[] = $buffer;
        }

        $header = str_getcsv($lines[0]);

        foreach($header as $columnTitle){
            if(!in_array($columnTitle,array('restaurant','user_id','days','time','flex','seats'))){
                throw new \Exception("Column name: $columnTitle in csv template contain something other than 'restaurant','user_id','days','time','flex','seats' See sample template under help menu for proper format.");
            }
        }

        unset( $lines[0] );

        if(empty($lines))
            throw new \Exception('No data was provided in attached file, please fill out the csv file according to the sample template.');

        $result = array();
        foreach( $lines as $line ){
            $explodedLine = str_getcsv( $line );

            foreach ($explodedLine as $key => $val) {
                if (is_null($explodedLine[$key]) || (string)$explodedLine[$key] == '') {
                    throw new \Exception('One or more of the students in the file is missing data. Please fix and re-upload the file. See the sample template under the help menu for a valid example.');
                }
            }

            $result[] = array_combine( $header, $explodedLine );
        }
 
    }catch(\Exception $e){
        return $e->getMessage();
    }

    // 3. loop through each reservation in array and insert a reservation post

    print_r($result);

    foreach($result as $res){

        echo "User id: " . $res["user_id"] ."\n";
        echo "Restaurant: " . $res["restaurant"] ."\n";

        //figure out current user id
        $user_id = $res["user_id"];

        //make sure post values are set
        $time = $res["time"];
        $days = $res["days"];
        $seats = $res["seats"];
        $flex = $res["flex"];
        
        $days = array_map('trim', explode(',',$days));
        
        //figure out what day it is today
        
        $today = date('l', strtotime( 'today' ));
        
        echo "Today is : ". $today ."\n";
        
        $match = false;
        
        foreach($days as $day){
            
            if($day == $today){
                 
                echo "Matched today with reservation day : " . $today . " = " . $day ."\n"; 
            
                $date = date("Y-m-d");
                $match = true;
            }
        }
        
        if(!$match)
            continue;
        
        echo "Matched res : " . print_r($res) ."\n";
        
        //fix the time and date formats
        date_default_timezone_set('America/New_York');
        $time = strtotime($date .' '. $time);
        $time_formatted = date("g:i a",$time);
        $date = date('m/d/y', strtotime($date));
        
        //get the Category set in the restaurant Users profile in the SoonSpoon specific section and use that to categorize new reservation
        $category = get_user_meta($user_id, 'category', true);          
        $cat_id = array(get_cat_ID( $category ));
        
        //check if flex seating type and adjust seat numbers accordingly
        if($flex=='1'){
            $seatsLessOne = intval($seats)-1;
            $seatStr = $seatsLessOne.'-'.$seats;
        }else{
            $seatStr = $seats;
        };
        
        //save the post!
        $post = array(
                'post_author'    => $user_id,
                'post_status'    => 'publish',
                'post_title'     => $category . ' - ' . $seatStr . ' seats' . ' on ' . $date . ' at ' . $time_formatted,
                'post_type'      => 'reservation',
                'Pending' => 'yes'
        );

        $post_id = wp_insert_post( $post, $wp_error );
        
        echo "Inserted post: " . print_r($post) . "\n";
        
        //update the custom fields and taxonomies related to the reservation
        update_post_meta($post_id, 'date_available', $date);
        update_post_meta($post_id, 'time_available', $time);
        update_post_meta($post_id, 'is_processed_restaurant', '0');
        update_post_meta($post_id, 'is_processed_user', '0');
        
        //check if flex seating type and adjust seat numbers accordingly
        if($flex=='1'){
            update_post_meta($post_id, 'seats_available', $seatStr);
            update_post_meta($post_id, 'is_underbookable', '1');
        }else{
            update_post_meta($post_id, 'seats_available', $seats);
            update_post_meta($post_id, 'is_underbookable', '0');
        };
        
        wp_set_post_categories( $post_id, $cat_id );
        
        //this one is needed to to make the wp query work correctly when pulling back posts 
        //for the add/cancel and front reservation tables, without it these tables would include booked reservations too
        wp_set_post_terms( $post_id,'pending','status');
        
        //call the function defined in the Tweet My Post plugin to auto tweet the post when added
        $shortlink = wp_get_shortlink($post_id);
        
        sleep(20);
        
        try{
			tmp_tweet_it($post_id,$shortlink);
		}catch(\Exception $e){
			echo "Encountered auto tweet error: " . $e->getMessage();
		}
    };

    
    
    
