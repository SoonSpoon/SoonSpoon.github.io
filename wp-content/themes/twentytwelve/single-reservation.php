<?php

/**

 * The Template for displaying all single Reservations.

 *

 * @package WordPress

 * @subpackage Twenty_Twelve

 * @since Twenty Twelve 1.0

 */

global $wpcf7_contact_form;



get_header(); ?>



	<div id="primary" class="site-content">

		<div id="content" role="main">

			<br>

			<br>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php if($post->post_status == 'booked') { ?>
					<div class="already-booked">
						<h2>Sorry, this reservation was just reserved...</h2>


						<p>But these are still available:</p>


						<table>
							<th> Restaurant </th>
							<th> Seats </th>
							<th><date> Date </date></th>
							<th> Time </th>

							<?php 
							$args = array('post_type' => 'reservation', 'post_status'=>'publish', 'meta_key' => 'date_available', 'posts_per_page' => '5', 'orderby' => 'date_available', 'order' => 'ASC');

							$loop = new WP_Query( $args );
								while ( $loop->have_posts()) : $loop->the_post(); ?>
									<tr class="reservation-line">
										<td><conor><?php foreach((get_the_category()) as $cat) { echo $cat->cat_name . ' '; } ?></conor></td>
										<td><seats><?php the_field('seats_available'); ?></seats></td>
										<td><?php the_field('date_available'); ?></td>
										<td><time><?php the_field('time_available'); ?></time></td>
										<td><a href="<?php the_permalink(); ?>">Reserve</a></td>
									</tr>
								<?php endwhile; ?>
						</table>

						<p><a href="/">View more available reservations &raquo;</a></p>
					</div>
					
				<?php } else { ?>

					<res> Please fill out this form correctly and completely. We use this information to secure your reservation. </res><res2>You will receive an email confirming your reservation. You must put your full name and real phone number or we will cancel your reservation.</res2>

					<div class="reservation-form">
						<?php echo do_shortcode("[contact-form-7 id='364' title='Restaurant Reservations']");  ?>
					</div>
				<?php } ?>

				

								

				<nav class="nav-single">

					<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>

					

				</nav><!-- .nav-single -->

		</div><!-- #content -->

	</div><!-- #primary -->

			<?php endwhile; // end of the loop. ?>



		



<?php get_sidebar(); ?>
<a href="http://www.soonspoon.com/terms-of-use/">Terms of Use </a> <a href="http://www.soonspoon.com/privacy-policy/">Privacy Policy</a>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-44697413-1', 'soonspoon.com');
  ga('send', 'pageview');

</script>

<?php get_footer(); ?>