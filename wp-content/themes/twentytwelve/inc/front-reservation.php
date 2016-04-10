<?php
// Restore original Post Data
wp_reset_postdata();

date_default_timezone_set('America/New_York');



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


?>

<thead>
<th style="width:20%">Name</th>
<th style="width:20%">Seats</th>
<th style="width:20%">Date</th>
<th style="width:20%">Time</th>
<th style="width:20%"></th>
</thead>
<?php

$cnt = 0;

while ( $loop->have_posts()) : $loop->the_post();

$date_available = date('m/d/y', strtotime(get_field('date_available')));

$timestamp = get_field('time_available');
		
$time_available = date("g:i a",$timestamp);




$cnt++;

$link = get_the_author_meta('url');

foreach((get_the_category()) as $cat) {
	$name = $cat->cat_name . ' ';
}

if(!$link)
	$link = '#';
?>
		<tr class="reservation-line">
			<td class="restaurant-name"><a href="<?php echo $link ?>" title="<?php echo $name ?>"><?php echo $name ?></a></td>
			<td><?php the_field('seats_available'); ?></td>
			<td><?php echo $date_available; ?></td>
			<td><?php echo $time_available ?></td>
			<td><a class="brown-block" href="<?php the_permalink(); ?>">Reserve</a></td>
		</tr>
<?php endwhile; ?>
