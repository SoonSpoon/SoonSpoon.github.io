<?php

/**

 * The main template file.

 *

 * This is the most generic template file in a WordPress theme

 * and one of the two required files for a theme (the other being style.css).

 * It is used to display a page when nothing more specific matches a query.

 * For example, it puts together the home page when no home.php file exists.

 *

 * Learn more: http://codex.wordpress.org/Template_Hierarchy

 *

 * @package WordPress

 * @subpackage Twenty_Twelve

 * @since Twenty Twelve 1.0

 */


/**
 * Always redirect to login screen if user isnt logged in
 */



get_header(); ?>



	<div id="primary" class="site-content">

		<div id="content" role="main">

	

<?php $args = array( 'post_type' => 'reservation');

$loop = new WP_Query( $args );

while ( $loop->have_posts() ) : $loop->the_post();

	the_title();

	echo '<div class="entry-content">';

	the_content();

	echo '</div>';

endwhile; ?>

			

		</div><!-- #content -->

	</div><!-- #primary -->



<?php get_sidebar(); ?>

<?php get_footer(); ?>

