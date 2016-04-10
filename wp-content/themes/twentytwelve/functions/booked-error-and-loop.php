<?php define('WP_USE_THEMES', false);  
require_once('../../../../wp-load.php'); ?>
<br/><br/>
<div class="already-booked">
						<h2>Sorry, this reservation has past or was just booked!</h2>


						<p>But these are still available:</p>


						<table>
								<thead>
									<th style="width:20%">Name</th>
									<th style="width:20%">Seats</th>
									<th style="width:20%">Date</th>
									<th style="width:20%">Time</th>
									<th style="width:20%"></th>
								</thead>

							<?php 
							// Restore original Post Data
							wp_reset_postdata();
							
							date_default_timezone_set('America/New_York');
							
							$today_str = date('m/d/y g:i a');
							
							$args = array('post_type' => 'reservation', 'post_status'=>'publish', 'meta_key' => 'time_available', 'orderby' => 'time_available', 'order' => 'ASC','status'=>'pending');

							$loop = new WP_Query( $args );
							
							while ( $loop->have_posts()) : $loop->the_post();
							
								$date_available = date('m/d/y', strtotime(get_field('date_available')));
								
								$timestamp = get_field('time_available');
										
								$time_available = date("g:i a",$timestamp);
								
								$timestamp_str= date('m/d/y g:i a',$timestamp);
								
								$today_obj = new DateTime($today_str);
								
								$timestamp_obj = new DateTime($timestamp_str);
								
								if($today_obj>$timestamp_obj){
									continue;
								}
								
								?>
										<tr class="reservation-line">
											<td><?php foreach((get_the_category()) as $cat) { echo $cat->cat_name . ' '; } ?></td>
											<td><?php the_field('seats_available'); ?></td>
											<td class="small"><?php echo $date_available; ?></td>
											<td class="small"><?php echo $time_available ?></td>
											<td><a href="<?php the_permalink(); ?>">Reserve</a></td>
										</tr>
							<?php endwhile; ?>
						</table>

						<p><a href="/">View more available reservations &raquo;</a></p>
					</div>