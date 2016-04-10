<table class="reservation">
	<thead>
		<th style="width:20%"> Seats </th>
		<th id="flex" style="width:10%">Flex?</th>
		<th style="width:20%">Date</th>
		<th style="width:20%"> Time </th>
		<th style="width:30%"> Status </th>
	</thead>
<?php 

$current_user = wp_get_current_user();

$user_id = $current_user->ID;

$user_name = $current_user->user_login;

date_default_timezone_set('America/New_York');

$today_str = date('m/d/y g:i a');

$args = array(
		'author' => $user_id,
		'post_type' => 'reservation', 
		'post_status'=>'publish', 
		'meta_key' => 'time_available', 
		'orderby' => 'time_available', 
		'order' => 'ASC',
		'status'=>'pending'
);

$loop = new WP_Query( $args );

if(!$loop->have_posts()){
	echo '</table>';
	}else{
		$count = -1;
		while ( $loop->have_posts()) : $loop->the_post();
		$count++;
		
		$date_available = date('m/d/y', strtotime(get_field('date_available')));

		$timestamp = get_field('time_available');
		
		$is_underbookable = get_field('is_underbookable');

		if($is_underbookable)
			$flexText = 'yes';
		else 
			$flexText = 'no';

		$time_available = date("g:i a",$timestamp);
		
		$timestamp_str= date('m/d/y g:i a',$timestamp);

		$today_obj = new DateTime($today_str);
		$timestamp_obj = new DateTime($timestamp_str);
		
		if($today_obj>$timestamp_obj){
			continue;
		}
		
		?>
				<tr class="reservation-line">
					<td><?php the_field('seats_available'); ?></td>
					<td><?php echo $flexText; ?></td>
					<td><?php echo $date_available; ?></td>
					<td><?php echo $time_available ?></td>
					<td><a data-id="<?php echo get_the_ID(); ?> " class="cancel brown-block">Cancel</a></td>
					<input class="cancel-input" type="hidden" name="row[<?php echo $count ?>][cancel_id]"/>
				</tr>
			<?php endwhile; ?>
			</table>
<?php }; ?>
	<div id="reservation-confirmation"><p><strong>Success!</strong> Your reservation has been posted! As a reminder, you can cancel reservations at any time by clicking the cancel button next to the reservation.</p></div>
	<br/><br/>
	<input type="button" id="add-reservation" title="Add Reservation Row" value="Add New Reservation">	

