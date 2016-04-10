<?php
/*
 * Script used to interact with Mailchimp Api for campaign creation, etc.
 */
 
 
global $wpdb, $table_prefix;
global $mailchimp;

if(!isset($wpdb))
{
	require_once (dirname(__FILE__).'/../../../wp-config.php');
	require_once (dirname(__FILE__).'/../../../wp-includes/wp-db.php');
	require_once (dirname(__FILE__).'/../../../wp-includes/query.php');
}

require_once dirname(__FILE__)."/mailchimp-api/src/Mailchimp.php";

try {
	$mailchimp = new Mailchimp('a3e78992bdd7d6ea4fd692bbc29b0c9f-us8'); 
} catch (Mailchimp_Error $e) {
	if ($e->getMessage()) {
		echo $e->getMessage() . "\n";
	} else {
		echo "An unknown error occurred trying to get segment list by list id" . "\n";
	}
}

/*
try {
	$mailChimpEmailTemplateId = 83865;
	$template = $mailchimp->templates->info($mailChimpEmailTemplateId);
} catch (Mailchimp_List_DoesNotExist $e) {
	echo "List does not exist: " . $e->getMessage() . "\n";
} catch (Mailchimp_Error $e) {
	if ($e->getMessage()) {
		echo $e->getMessage() . "\n";
	} else {
		echo "An unknown error occurred trying to get segment list by list id" . "\n";
	}
}

print_r($template);
die;
* 
* */

/*try {
	$mailChimpAllSubscribersListId = '4783eb7908';
	$segments = $mailchimp->lists->segments($mailChimpAllSubscribersListId);
} catch (Mailchimp_List_DoesNotExist $e) {
	echo "List does not exist: " . $e->getMessage() . "\n";
} catch (Mailchimp_Error $e) {
	if ($e->getMessage()) {
		echo $e->getMessage() . "\n";
	} else {
		echo "An unknown error occurred trying to get segment list by list id" . "\n";
	}
}

foreach($segments["saved"] as $segment){
	echo "$".strtolower(str_replace(' ', '', $segment["name"])) . " = " . $segment["id"] . ";" . "\n";	
}

*/

/*******************
 * Get list of upcoming reservations
**********************/

$args = array('post_type' => 'reservation', 
              'post_status'=>'publish', 
              'meta_key' => 'time_available', 
              'orderby' => 'time_available', 
              'order' => 'ASC',
              'meta_query' => array(
                array(
                    'key'=> 'time_available',
                    'value'=> strtotime('now'),
                    'compare' =>  '>='
                ),
              ),
              'status'=>'pending');

$loop = new WP_Query( $args );

$reservations = [];

while ( $loop->have_posts()) : $loop->the_post();

	foreach((get_the_category()) as $cat) {
		$name = $cat->cat_name;
	}

	if(!$link)
		$link = 'http://www.soonspoon.com';
	
	date_default_timezone_set('America/New_York');
		
	$date_available = date('m/d/y', strtotime(get_field('date_available')));
	$timestamp = get_field('time_available');
	$seats = get_field('seats_available');
	$time_available = date("g:i a",$timestamp);
	$reservation_link = get_permalink();

	$link = get_the_author_meta('url');

	$reservations[] = array("restaurant_info"=>array("link"=>$link,"name"=>$name),"seats"=>$seats,"date"=>$date_available,"time_available"=>$time_available,"reservation_link"=>$reservation_link);
	
endwhile;

/*******************
* Determine which lists to send to based on the date/time, cronjob runs at 00 and 30 minute marks of every hour of every day
**********************/

/**
 * Mailchimp segment ids
 * 
 * */
 
$sun3pm = 10945;
$sun4pm = 10949;
$sun530pm = 10953;
$mon3pm = 10957;
$mon4pm = 10961;
$mon530pm = 10965;
$tues3pm = 10969;
$tues4pm = 10973;
$tues530pm = 10977;
$wed3pm = 10785;
$wed4pm = 10981;
$wed530pm = 10985;
$thurs3pm = 10989;
$thurs4pm = 10993;
$thurs530pm = 10997;
$fri3pm = 11001;
$fri4pm = 11005;
$fri530pm = 11009;
$sat3pm = 11013;
$sat4pm = 11017;
$sat530pm = 11021;

$daily3pmTemplateId = 116977;
$daily4pmTemplateId = 117785;
$daily530pmTemplateId = 117793;

$date = getdate();

$monthNum = $date["mon"];
$dayNum = $date["mday"];
$wday = $date["weekday"];
$hour = $date["hours"];
$minutes = $date["minutes"];

$segmentId = null;
$templateId = null;

echo "Processing current day : " . $wday . " current hour: " . $hour . " current minutes: ". $minutes. "\n";

switch ($wday) {
    case "Monday":
		if($hour == 15 && $minutes < 5){
			$segmentId = $mon3pm;
			$templateId = $daily3pmTemplateId;
		}
		if($hour == 16 && $minutes < 5){
			$segmentId = $mon4pm;
			$templateId = $daily4pmTemplateId;
		}
		if($hour == 17 && $minutes >= 30){
			$segmentId = $mon530pm;
			$templateId = $daily530pmTemplateId;
		}				
        break;
    case "Tuesday":
		if($hour == 15 && $minutes < 5){
			$segmentId = $tues3pm;
			$templateId = $daily3pmTemplateId;
		}
		if($hour == 16 && $minutes < 5){
			$segmentId = $tues4pm;
			$templateId = $daily4pmTemplateId;
		}
		if($hour == 17 && $minutes >= 30){
			$segmentId = $tues530pm;
			$templateId = $daily530pmTemplateId;
		}				
        break;
    case "Wednesday":
		if($hour == 15 && $minutes < 5){
			$segmentId = $wed3pm;
			$templateId = $daily3pmTemplateId;
		}
		if($hour == 16 && $minutes < 5){
			$segmentId = $wed4pm;
			$templateId = $daily4pmTemplateId;
		}
		if($hour == 17 && $minutes >= 30){
			$segmentId = $wed530pm;
			$templateId = $daily530pmTemplateId;
		}				
        break;
	case "Thursday":
		if($hour == 15 && $minutes < 5){
			$segmentId = $thurs3pm;
			$templateId = $daily3pmTemplateId;
		}
		if($hour == 16 && $minutes < 5){
			$segmentId = $thurs4pm;
			$templateId = $daily4pmTemplateId;
		}
		if($hour == 17 && $minutes >= 30){
			$segmentId = $thurs530pm;
			$templateId = $daily530pmTemplateId;
		}				
        break;
	case "Friday":
		if($hour == 15 && $minutes < 5){
			$segmentId = $fri3pm;
			$templateId = $daily3pmTemplateId;
		}
		if($hour == 16 && $minutes < 5){
			$segmentId = $fri4pm;
			$templateId = $daily4pmTemplateId;
		}
		if($hour == 17 && $minutes >= 30){
			$segmentId = $fri530pm;
			$templateId = $daily530pmTemplateId;
		}				
        break;
	case "Saturday":
		if($hour == 15 && $minutes < 5){
			$segmentId = $sat3pm;
			$templateId = $daily3pmTemplateId;
		}
		if($hour == 16 && $minutes < 5){
			$segmentId = $sat4pm;
			$templateId = $daily4pmTemplateId;
		}
		if($hour == 17 && $minutes >= 30){
			$segmentId = $sat530pm;
			$templateId = $daily530pmTemplateId;
		}				
        break;
	case "Sunday":
		if($hour == 15 && $minutes < 5){
			$segmentId = $sun3pm;
			$templateId = $daily3pmTemplateId;
		}
		if($hour == 16 && $minutes < 5){
			$segmentId = $sun4pm;
			$templateId = $daily4pmTemplateId;
		}
		if($hour == 17 && $minutes >= 30){
			$segmentId = $sun530pm;
			$templateId = $daily530pmTemplateId;
		}				
        break;
}

//$segmentId = $wed3pm;
//$templateId = $daily3pmTemplateId;

/*******************
* Apply current reservation content to pre-defined template id $mailChimpEmaiTemplateId
**********************/

$html = <<<HTML

<div style="width:100%;margin:0 auto;">
                          <table style="table-layout:fixed;margin-left: 55px;margin-top:25px;" id="front-table" class="reservation"> 
                            <thead>
                            <tr><th id="nameHeader">Name</th>
                            <th id="seatsHeader">Seats</th>
                            <th id="dateHeader">Date</th>
                            <th id="timeHeader">Time</th>
                            <th id="reserveHeader"></th>
                            </tr>
                            </thead>
		
                            <tbody>
HTML;

if(count($reservations > 0) && !is_null($segmentId)){
	
	foreach($reservations as $reservation){
		
		$html.= <<<HTML
																				<tr class="reservation-line">
																					<td class="restaurant-name"><a href="{$reservation["restaurant_info"]["link"]}" target="_blank" title="{$reservation["restaurant_info"]["name"]}">{$reservation["restaurant_info"]["name"]}</a></td>
																					<td>{$reservation["seats"]}</td>
																					<td>{$reservation["date"]}</td>
																					<td>{$reservation["time_available"]}</td>
																					<td><a class="lnk-blue brown-block" href="{$reservation["reservation_link"]}">Reserve</a></td>
																				</tr>

HTML;
		
	}

}

$html .= <<<HTML

                    			</tbody>
                    	   </table></div>

HTML;

if(count($reservations > 0) && !is_null($segmentId)){

	try {
		
		$options = array(
			"list_id"=>'4783eb7908',
			"subject"=>'Your Spontaneous Reservations',
			"from_email"=>'shout@soonspoon.com',
			"from_name"=>'SoonSpoon',
			"title" => 'Auto-generated: ' . $monthNum . "-" . $dayNum . " ". ($hour-12) . $minutes, 
			"template_id" => $templateId,
			"generate_text" => true
		);
		
		$content = array(
			"sections"=> array(
				"body"=> $html
			)
		);
		
		$segment_opts = array(
			"saved_segment_id"=>$segmentId
		);

		$campaign = $mailchimp->campaigns->create('regular', $options, $content, $segment_opts);
		
		print_r($campaign);
		
		
	}catch (Mailchimp_Error $e) {
		if ($e->getMessage()) {
			echo $e->getMessage() . "\n";
		} else {
			echo "An unknown error occurred trying to create campaign" . "\n";
		}
	}
	
	try {

	$cid = $campaign["id"];
	$campaign = $mailchimp->campaigns->send($cid);
		
	print_r($campaign);	
		
	}catch (Mailchimp_Error $e) {
		if ($e->getMessage()) {
			echo $e->getMessage() . "\n";
		} else {
			echo "An unknown error occurred trying to send test campaign" . "\n";
		}
	}
	


}else{
	echo "No reservations to send or no matching segments." . "\n";
}






	








