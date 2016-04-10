<?php 

global $wpdb, $table_prefix;

if(!isset($wpdb))
{
	require_once (dirname(__FILE__).'/../../../wp-config.php');
	require_once (dirname(__FILE__).'/../../../wp-includes/wp-db.php');
}

$sent_from_number_list = array('1','2','3','4','5','6');

$sent_from_count = count($sent_from_number_list);

$numbers = array('1-800-000-0001',
		'1-800-000-0002',
		'1-800-000-0003',
		'1-800-000-0004',
		'1-800-000-0005',
		'1-800-000-0006',
		'1-800-000-0007',
		'1-800-000-0008',
		'1-800-000-0009',
		'1-800-000-0010',
		'1-800-000-0011',
		'1-800-000-0012',		
		'1-800-000-0013',
		'1-800-000-0014',
		'1-800-000-0015',
		'1-800-000-0016',
		'1-800-000-0017',
);

foreach($numbers as $number){

	$sql = "SELECT twilio_setting_value
	    			FROM uma_twilio_settings t
	    			WHERE t.twilio_setting_id = 2";

	$count_record = $wpdb->get_results( $sql );
	$count = intval($count_record[0]->twilio_setting_value);

	echo 'to number: ' . $number . "\n";
	
	echo 'existing count from db: ' . $count . "\n";
	
	$sent_from_number = $sent_from_number_list[$count++ % $sent_from_count];

	$sql = "UPDATE uma_twilio_settings t
	SET t.twilio_setting_value = $count
	WHERE t.twilio_setting_id = 2";
	
	$processed = $wpdb->get_results( $sql );
	
	echo 'updated count ' . $count . "\n";
	
	echo 'sent from number ' . $sent_from_number . "\n";
	
	echo '-------------'. "\n";
}


?>