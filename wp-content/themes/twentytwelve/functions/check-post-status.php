<?php 
header('Content-Type: application/json');
define('WP_USE_THEMES', false);  
require_once('../../../../wp-load.php'); 

$check_id = (isset($_GET['check_id'])) ? $_GET['check_id'] : 745;

$timestamp = get_post_field('time_available',$check_id );
$is_underbookable = get_post_field('is_underbookable',$check_id );
$seats_available = get_post_field('seats_available',$check_id );

date_default_timezone_set('America/New_York');

$today_str = date('m/d/y g:i a');

$timestamp_str= date('m/d/y g:i a',$timestamp);

$today_obj = new DateTime($today_str);

$timestamp_obj = new DateTime($timestamp_str);

if($today_obj>$timestamp_obj){
	$status = array('post_status' => 'past','seats_available'=>$seats_available,'is_underbookable'=>$is_underbookable);
}else{
	$status = get_post_status($check_id);

	$status = array('post_status' => $status,'seats_available'=>$seats_available,'is_underbookable'=>$is_underbookable);
}

$status = json_encode($status);

echo $status;

?>