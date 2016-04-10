<table class="reservation">
	<thead>
		<th style="width:20%">Seats</th>
		<th style="width:30%">Date</th>
		<th style="width:20%">Time</th>
		<th style="width:30%">Diner</th>
	</thead>
<?php 

$current_user = wp_get_current_user();

$user_id = $current_user->ID;

date_default_timezone_set('America/New_York');

$today_str = date('m/d/y g:i a');

$args = array('author'=>$user_id,'post_type' => 'reservation', 'post_status' => array( 'booked'), 'meta_key' => 'time_available', 'orderby' => 'time_available', 'order' => 'ASC');

$loop = new WP_Query( $args );
$count = -1;

if(!$loop->have_posts()){
	echo '</table><center><p>No booked reservations to display.</p></center>';
}else{
	while ( $loop->have_posts()) : $loop->the_post(); 
	$count++;
	
	$date_available = date('m/d/y', strtotime(get_field('date_available')));

	$timestamp = get_field('time_available');
	
	$timestamp_str= date('m/d/y g:i a',$timestamp);

	$today_obj = new DateTime($today_str);
	$timestamp_obj = new DateTime($timestamp_str);

	if($today_obj>$timestamp_obj){
		continue;
	}
		
	$time_available = date("g:i a",$timestamp);
	
	?>
		<tr class="reservation-line">
			<td><?php the_field('seats_available'); ?></td>
			<td><?php echo $date_available; ?></td>
			<td><?php echo $time_available ?></td>
			<td class="diner-info"><div class="force-wrapper"><span><?php the_field('diner_name'); ?></span></div><a href="tel:+<?php the_field('diner_phone'); ?>"><?php the_field('diner_phone'); ?></a></br><div class="force-wrapper"><a href="mailto:<?php the_field('diner_email'); ?>"><?php the_field('diner_email'); ?></a></div><?php the_field('reservation_notes'); ?></td>
		</tr>
	<?php endwhile; ?>
	</table>
<?php }; ?>