<?php

/**
 * Template Name: Restaraunt Dashboard - Booked Listing
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in Twenty Twelve consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); 

$current_user = wp_get_current_user();

$user_id = $current_user->ID;

$user_name = $current_user->user_login;
?>

	<div id="primary" class="site-content-front">
		<a id ="back" href="<?php echo home_url().'/restaurants';?>" title="Logout">Add/Cancel Reservations</a>
		<a id ="logout" href="<?php echo wp_logout_url( $redirect ); ?>" title="Logout">Log out - <?php echo $user_name ?></a><br/>
		
		<div id="content" role="main">
			
			<?php while ( have_posts() ) : the_post(); ?>

				<?php if ( has_post_thumbnail() ) : ?>

					<div class="entry-page-image">

						<?php the_post_thumbnail(); ?>

					</div><!-- .entry-page-image -->

				<?php endif; ?>

				<?php get_template_part( 'content', 'page' ); ?>


			<?php endwhile; // end of the loop. ?>
	
			<div id="table-wrapper">
				<?php get_template_part( 'inc/booked', 'reservation' ); ?>
			</div>
		 
		
	</div><!-- #primary -->

<script>

$(document).ready(function() {

	//refresh table every 10 seconds to make sure only unbooked reservations are showing
	
 	setInterval(refreshTable, 10000);

	function refreshTable(){
		$.post('/wp-admin/admin-ajax.php', { action: "update_booked" }, function(response) {
			$('#table-wrapper').html(response);
		});
	};


});  
	
</script>	
	
<script>

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-44697413-1', 'soonspoon.com');
  ga('send', 'pageview');

</script>

<?php get_footer(); ?>