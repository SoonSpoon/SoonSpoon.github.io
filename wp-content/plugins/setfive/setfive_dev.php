<?php   
/* 
Plugin Name: Setfive's SoonSpoon Plugin 
Plugin URI: http://www.setfive.com 
Description: Plugin for adding custom functionality to SoonSpoon's WP installation
Author: Setfive Consulting, LLC. 
Version: 1.0 
Author URI: http://www.setfive.com 
*/  
?> 

<?php 

/**
 * Function creates the new restaurant role and then is called on plugin activation hook
 */

function setfive_dev_activate() {

	$result = add_role(
			'restaurant',
			__( 'Restaurant' ),
			array(
					'read'         => true,  // true allows this capability
					'edit_posts'   => true,
					'delete_posts' => true, // Use false to explicitly deny
					'publish_posts' => true, // Use false to explicitly deny
					'upload_files' => true,
					'delete_published_posts' => true,
					'edit_published_posts' => true,
					
			)
	);
}
register_activation_hook( __FILE__, 'setfive_dev_activate' );


/**
 * Function checks what page an unlogged in user is currently on and forces them to log in 
 * if they are trying to access restauraunt only page, called on initial page load (wp hook) 
 */
function login_redirect() {

	// Current Page
	global $pagenow;
	
	$path=$_SERVER['REQUEST_URI'];
	
	if (!is_admin()){
		//force user to log in if on the restaurant only pages
		if($path == '/restaurants/' || $path == '/add-and-cancel/' || $path == '/booked-listing/'){
			//Check to see if user in not logged in and not on the login page
			if(!is_user_logged_in() && $pagenow != 'wp-login.php'){
				// If user is, Redirect to Login form.
				wp_redirect( wp_login_url() );
				exit;
			}
		}
	}
}
// add the block of code above to the WordPress template
add_action( 'wp', 'login_redirect' );

/**
 * Function removes the top wordpress navbar if user is logged in but not an admin (aka Restaraunt Users), called after theme setup
 */

function remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
	  show_admin_bar(false);
	}
}

add_action('after_setup_theme', 'remove_admin_bar');

/**
 * Function redirects Restaraunt Users to the Restaraunt Dashboard after successfully logging into the site.
 */
function restaurant_login_redirect( $redirect_to, $request, $user ){
    global $user;
    
    //call helper function to check if user has Restaurant role and isnt an admin
    if(isRestaurant($user)){
    	return home_url().'/restaurants';
    }else{
    	return $redirect_to;
    }
}

add_filter("login_redirect", "restaurant_login_redirect", 10, 3);

/**
 * Function adds SoonSpoon specific fields to the Restaurant User Profile screen.
 */

function my_show_extra_profile_fields( $user ) { 

if(isRestaurant($user)):
?>

	<h3>SoonSpoon Information</h3>

	<table class="form-table">

		<tr>
			<th><label for="phone">Telephone Number</label></th>
			
			<td>
				<input required type="tel" style="width:1000px" name="twilio_phone" id="phone" value="<?php echo esc_attr( get_the_author_meta( 'twilio_phone', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter a comma separated list of the 10 digit phone numbers that will receive SMS messages from Twilio.</span>
			</td>
		</tr>
		
		<tr>	
			<th><label for="hours">Monday Hours</label></th>			
			<td>
				<input required type="time" name="mon_hours_from" value="<?php echo esc_attr( get_the_author_meta( 'mon_hours_from', $user->ID ) ); ?>" class="regular-text" /><br />
				<input required type="time" name="mon_hours_to" value="<?php echo esc_attr( get_the_author_meta( 'mon_hours_to', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter opening and closing hours for Monday.</span>
			</td>
		</tr>

		<tr>	
			<th><label for="hours">Tuesday Hours</label></th>

			<td>
				<input required type="time" name="tues_hours_from" value="<?php echo esc_attr( get_the_author_meta( 'tues_hours_from', $user->ID ) ); ?>" class="regular-text" /><br />
				<input required type="time" name="tues_hours_to" value="<?php echo esc_attr( get_the_author_meta( 'tues_hours_to', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter opening and closing hours for Tuesday.</span>
			</td>
		</tr>
		
		<tr>	
			<th><label for="hours">Wednesday Hours</label></th>
			
			<td>
				<input required type="time" name="wed_hours_from" value="<?php echo esc_attr( get_the_author_meta( 'wed_hours_from', $user->ID ) ); ?>" class="regular-text" /><br />
				<input required type="time" name="wed_hours_to" value="<?php echo esc_attr( get_the_author_meta( 'wed_hours_to', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter opening and closing hours for Wednesday.</span>
			</td>
		</tr>
		
		<tr>	
			<th><label for="hours">Thursday Hours</label></th>
			<td>
				<input required type="time" name="thurs_hours_from" value="<?php echo esc_attr( get_the_author_meta( 'thurs_hours_from', $user->ID ) ); ?>" class="regular-text" /><br />
				<input required type="time" name="thurs_hours_to" value="<?php echo esc_attr( get_the_author_meta( 'thurs_hours_to', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter opening and closing hours for Thursday.</span>
			</td>
		</tr>
		
		<tr>	
			<th><label for="hours">Friday Hours</label></th>
			<td>
				<input required type="time" name="fri_hours_from" value="<?php echo esc_attr( get_the_author_meta( 'fri_hours_from', $user->ID ) ); ?>" class="regular-text" /><br />
				<input required type="time" name="fri_hours_to" value="<?php echo esc_attr( get_the_author_meta( 'fri_hours_to', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter opening and closing hours for Friday.</span>
			</td>
		</tr>
		
		<tr>	
			<th><label for="hours">Saturday Hours</label></th>
			<td>
				<input required type="time" name="sat_hours_from" value="<?php echo esc_attr( get_the_author_meta( 'sat_hours_from', $user->ID ) ); ?>" class="regular-text" /><br />
				<input required type="time" name="sat_hours_to" value="<?php echo esc_attr( get_the_author_meta( 'sat_hours_to', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter opening and closing hours for Saturday.</span>
			</td>
		</tr>
		
		<tr>	
			<th><label for="hours">Sunday Hours</label></th>
			<td>
				<input required type="time" name="sun_hours_from" value="<?php echo esc_attr( get_the_author_meta( 'sun_hours_from', $user->ID ) ); ?>" class="regular-text" /><br />
				<input required type="time" name="sun_hours_to" value="<?php echo esc_attr( get_the_author_meta( 'sun_hours_to', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter opening and closing hours for Sunday.</span>
			</td>
		</tr>
		
		<tr>	
			<th><label for="hours">Category</label></th>
			
			<td>
				<input required type="text" name="category" id="category" value="<?php echo esc_attr( get_the_author_meta( 'category', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter the category/restaurant name to be used on for classifying reservations.</span>
			</td>
		</tr>

	</table>
<?php 
endif;
}

add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

/**
 * Function saves the SoonSpoon specific fields input on the User Profile screen.
 */

function my_save_extra_profile_fields( $user_id ) {
	
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;
	
	$user = get_userdata( $user_id );

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_usermeta( $user_id, 'twilio_phone', $_POST['twilio_phone'] );
	update_usermeta( $user_id, 'mon_hours_from', $_POST['mon_hours_from'] );
	update_usermeta( $user_id, 'mon_hours_to', $_POST['mon_hours_to'] );
	update_usermeta( $user_id, 'tues_hours_from', $_POST['tues_hours_from'] );
	update_usermeta( $user_id, 'tues_hours_to', $_POST['tues_hours_to'] );
	update_usermeta( $user_id, 'wed_hours_from', $_POST['wed_hours_from'] );
	update_usermeta( $user_id, 'wed_hours_to', $_POST['wed_hours_to'] );
	update_usermeta( $user_id, 'thurs_hours_from', $_POST['thurs_hours_from'] );
	update_usermeta( $user_id, 'thurs_hours_to', $_POST['thurs_hours_to'] );
	update_usermeta( $user_id, 'fri_hours_from', $_POST['fri_hours_from'] );
	update_usermeta( $user_id, 'fri_hours_to', $_POST['fri_hours_to'] );
	update_usermeta( $user_id, 'sat_hours_from', $_POST['sat_hours_from'] );
	update_usermeta( $user_id, 'sat_hours_to', $_POST['sat_hours_to'] );
	update_usermeta( $user_id, 'sun_hours_from', $_POST['sun_hours_from'] );
	update_usermeta( $user_id, 'sun_hours_to', $_POST['sun_hours_to'] );
	update_usermeta( $user_id, 'category', $_POST['category'] );
}

add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

/**
 * Function determines if the category field input in the Soonspoon specific settings of the user profile is already in user by another user. 
 * If in use, it flashes a message reminding the admin that if they are creating a new user they should choose a different category name. 
 */

function validate_extra(&$errors, $update = null, &$user  = null)
{
	if(isset($_POST['category'])){
			
			$term = term_exists($_POST['category'], 'category');

			if(!$term)
				$result = wp_create_category($category = $_POST['category']);
			else
				my_admin_notice();	
	}
}

function my_admin_notice() {
	global $pagenow;
	if ( $pagenow == 'user-edit.php' ) {	
	?>
	    <div class="updated">
	        <p><?php _e( 'This category you saved already exists, if you are saving a user for the first time, choose a different category name.', 'my-text-domain' ); ?></p>
	    </div>
	<?php
    }
}

add_action( 'admin_notices', 'my_admin_notice' );

add_action( 'user_profile_update_errors', 'validate_extra' );


/**
 * ----------------------------------------------AJAX HANDLERS---------------------------------------------------------******
 */


/**
 * Function responds to the button click of the 'Batch Send Messages' in the Twilio User Setting screen. 
 * Loads the twilio user message script see wp-content/plugins/setfive/send-usermessages.php
 */

add_action( 'wp_ajax_send_usermessages', 'ajax_send_usermessages' );

function ajax_send_usermessages() {

	//update the message text and user list with the most recent values from the settings page
	
	if (isset($_POST['message'])){
		$msg = $_POST['message'];
		update_option( 'twilio_user_message_prefix', $msg );
	}
	
	if (isset($_POST['userlist'])){
		$list = $_POST['userlist'];
		update_option( 'twilio_user_csv_list', $list );
	}
	
	echo $html = include_output('send-usermessages.php');

	die(); // this is required to return a proper result
}

/**
 * Function updates the front page reservation table and is called every 10 seconds by javascript within the front-page.php template
 * Loads template-part located at themes/twentytwelve/inc/front-reservation.php
 */

function ajax_update_front() {

	$reservation_table_html = get_template_directory().'/inc/front-reservation.php';

	echo $html = include_output($reservation_table_html);
	
	die(); // this is required to return a proper result
}

add_action( 'wp_ajax_update_front', 'ajax_update_front' );
add_action( 'wp_ajax_nopriv_update_front', 'ajax_update_front' );

/**
 * Function updates the restaurant booked reservation page table and is called 
 * every 10 seconds by javascript within the front-page.php template
 * Loads template-part located at themes/twentytwelve/inc/booked-reservation.php
 */

add_action( 'wp_ajax_update_booked', 'ajax_update_booked' );

function ajax_update_booked() {

	$reservation_table_html = get_template_directory().'/inc/booked-reservation.php';

	echo $html = include_output($reservation_table_html);

	die(); // this is required to return a proper result
}

/**
 * 
 * Function responds to the click of an 'Add' button next to each reservation on the Restaurant Add/Cancel page. 
 * Creates a new reservation post, tweets it, and returns a new version of the html reservation table to be reloaded upon successful update.  
 */

add_action( 'wp_ajax_update_reservation', 'ajax_update_reservation' );

function ajax_update_reservation() {

	//figure out current user id
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;

	//make sure post values are set
	if(isset($_POST['time'])){$time = $_POST['time'];};
	if(isset($_POST['date'])){$date = $_POST['date'];};
	if(isset($_POST['seats'])){$seats = $_POST['seats'];};
	if(isset($_POST['flex'])){$flex = $_POST['flex'];};
	
	//fix the time and date formats
	date_default_timezone_set('America/New_York');
	$time = strtotime($date . $time);
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
	tmp_tweet_it($post_id,$shortlink);
	
	//return new html to "refresh" the reservation table on the add/cancel screen
	$reservation_table_html = get_template_directory().'/inc/reservation-table.php';
	echo $html = include_output($reservation_table_html);

	die(); // this is required to return a proper result
}


/**
 *
 * Function responds to the click of an 'Cancel' button next to each reservation on the Restaurant Add/Cancel page.
 * Cancels an existing reservation by updating it to 'trash' status and returns the new html table to refresh.
 */

add_action( 'wp_ajax_cancel_reservation', 'ajax_cancel_reservation' );

function ajax_cancel_reservation() {
	
	//find current user id
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;

	//make sure cancel_id is set so we know what post to cancel
	if(isset($_POST['cancel_id'])){
		//run query to cancel reservation
		$post = array(
					'ID'             => $_POST['cancel_id'],
					'post_status'    => 'trash'
		);
	
		$post_id = wp_update_post( $post );
	
		//return table html to refresh the page
		$reservation_table_html = get_template_directory().'/inc/reservation-table.php';
	
		echo $html = include_output($reservation_table_html);
	
		die(); 
	}
}

/**
 * ----------------------------------------------BACKEND UI UPDATES---------------------------------------------------------******
 */


/**
 * Create the Admin Menu Page for Twilio Settings
 */


function setfive_plugin_settings() {
	add_menu_page('SoonSpoon Twilio Settings', 'SoonSpoon Twilio Settings', 'administrator', 'twilio_settings', 'twilio_display_settings');
}

add_action('admin_menu', 'setfive_plugin_settings');

/**
 * Function to implement the HTML form required for adding Twilio settings fields.
 */

function twilio_display_settings() {
	$sid = (get_option('twilio_sid') != '') ? get_option('twilio_sid') : '';
	$auth_token = (get_option('twilio_auth_token') != '') ? get_option('twilio_auth_token') : '';
	$users = (get_option('twilio_user_csv_list') != '') ? get_option('twilio_user_csv_list') : '';
	$restaurant_from_phone = (get_option('twilio_restaurant_from_phone') != '') ? get_option('twilio_restaurant_from_phone') : '';
	$restaurant_message_prefix = (get_option('twilio_restaurant_message_prefix') != '') ? get_option('twilio_restaurant_message_prefix') : "Hi it's SoonSpoon. We're confirming the following reservation:";
	$user_from_phones = (get_option('twilio_user_from_phones') != '') ? get_option('twilio_user_from_phones') : '';
	$user_message_prefix = (get_option('twilio_user_message_prefix') != '') ? get_option('twilio_user_message_prefix') : "Hi it's SoonSpoon. We have the following reservations available:";

	$html = '<div class="wrap"><form action="options.php" method="post" name="options">
<h2>SoonSpoon Twilio Settings</h2>
' . wp_nonce_field('update-options') . '
<table class="form-table" width="100%" cellpadding="10">
<tbody>
<tr>
	<td scope="row" align="left">
 	<label>Twilio Account SID:</label><input style="width:300px" type="text" name="twilio_sid" value="' . $sid . '" /></td>
</tr>

<tr>
	<td scope="row" align="left">
 	<label>Twilio Account Auth Token:</label><input style="width:300px" type="text" name="twilio_auth_token" value="' . $auth_token . '" /></td>
</tr>						
						
<tr>
	<td scope="row" align="left">
 	<label>From Phone #s for Users (CSV and number format 888-888-8888):</label><input style="width:1000px" type="text" name="twilio_user_from_phones" value="' . $user_from_phones . '" /></td>
</tr>
					
<tr>
	<td scope="row" align="left">
 	<label>From Phone #s for Restaurants (CSV and format: 888-888-8888):</label><input style="width:1000px" type="text" name="twilio_restaurant_from_phone" value="' . $restaurant_from_phone . '" /></td>
</tr>
						
<tr>
	<td scope="row" align="left">
 	<label style="display:block">Message Prefix for Restaurants:</label><textarea rows="4" cols="20" name="twilio_restaurant_message_prefix">'.$restaurant_message_prefix.'</textarea>		
	</td>
</tr>			
										

<tr>
	<td scope="row" align="left">
	<input type="submit" name="Submit" value="Update Settings" /><br/>
	<h3>Batch Message to Users</h3>
 	<label style="display:block">Message for Users:</label><textarea rows="4" cols="20" name="twilio_user_message_prefix">'.$user_message_prefix.'</textarea></td>
</tr>
						
<tr>
	
	<td id="sendUserMessageWrapper" scope="row" align="left">
 		<label style="display:block">Twilio User Phone List (one per line 18001234567 or +18001234567 format):</label> 
 		<textarea rows="20" cols="100" name="twilio_user_csv_list">'.$users.'</textarea>
	</td>
</tr>	
<tr><td scope="row" align="left"><input id="batchSendUserMessage" type="button" name="send_user_messages" value="Batch Send to Users" /></td></tr>								
</tbody>

</table>
 <input type="hidden" name="action" value="update" />

 <input type="hidden" name="page_options" value="twilio_user_csv_list,twilio_restaurant_from_phone,twilio_restaurant_message_prefix,twilio_user_from_phones,twilio_user_message_prefix,twilio_auth_token,twilio_sid" />

 </form></div>			
';

	echo $html;

}

/**
 * Change the Login Logo to SoonSpoon
 */

function my_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url('<?php echo get_bloginfo('template_directory');?>/img/Soon-Spoon-Logo.png');
            width:100%;
            height: 120px;
            background-size: 100%;
        }
       
    </style>
<?php }

add_action( 'login_enqueue_scripts', 'my_login_logo' );

/**
 * Change the Login Logo url
 */

function my_login_logo_url() {
	return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
	return 'SoonSpoon - Last Minute Reservations. With Benefits';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );


/**
 * Add Ability for users to set thier Tweet My Post twitter handle for auto post
 */

//function action for admin_menu hook to add pages
function add_tmp_page_spoon()
{
	add_submenu_page( 'profile.php', "Twitter Handle", "Twitter Handle", 'read', "tmp_user_page_handle", "tmp_user_page_spoon" );
}
add_action('admin_menu','add_tmp_page_spoon');

//TMP user page code
function tmp_user_page_spoon()
{
	global $current_user;
	get_currentuserinfo();
	add_option("ID-".$current_user->ID);
	if(isset($_POST['twitter']))
		update_option("ID-".$current_user->ID,$_POST['twitter']);
	//echo get_option($current_user->user_login);
	//echo $current_user->ID;
	echo "<div class=\"wrap\">";
	echo "<h2>Tweet My Post</h2>";
	echo "<form method=\"post\" action=\"?page=tmp_user_page_handle\">";
	settings_fields( 'tmp-option' );
	//do_settings_fields('tmp-option');
	echo "<table class=\"form-table\">";
	echo "<tr valign=\"top\"><th scope=\"row\">Your Twitter Handle</th>";
	echo "<td>@<input type=\"text\" name=\"twitter\" value=\"".get_option("ID-".$current_user->ID)."\"/></td>";
	echo "</tr>";
	echo "</table><p class=\"submit\"><input type=\"submit\" class=\"button-primary\" value=\"Save Changes\" /></p></form></div>";
}

/**
 * ----------------------------------------------HELPER FUNCTIONS--------------------------------------------------------------------******
 */


/**
 * Helper function to Check if user object passed in is restaurant
 *
 */

function isRestaurant($user){
	//check for restaurants
	if( isset( $user->roles ) && is_array( $user->roles ) ){
		if( in_array( "restaurant", $user->roles ) && !in_array( "administrator", $user->roles ) ){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

/**
 * Helper function to load template html from file for return via ajax
 */

function include_output($filename)
{
	ob_start();
	include $filename;
	$contents = ob_get_contents();
	ob_end_clean();
	return $contents;
}

/**
 * Add the js file used for the ajax call for Batch Send User Messages in the Twilio settings screen
 */

function custom_admin_js() {
	$url = get_option('siteurl');
	$url = get_bloginfo('template_directory') . '/js/wp-admin.js';
	echo '"<script type="text/javascript" src="'. $url . '"></script>"';
}
add_action('admin_footer', 'custom_admin_js');

add_filter('upload_mimes', 'my_upload_mimes');

function my_upload_mimes ( $existing_mimes=array() ) {
    $existing_mimes['csv'] = 'text/csv';
    return $existing_mimes;
}

