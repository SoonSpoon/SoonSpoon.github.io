<?php
/**
 * Template Name: Full-width Page Template, No Sidebar
 *
 * Use this page template to remove the sidebar from any page.
 *
 * Tip: to remove the sidebar from all posts and pages simply remove
 * any active widgets from the Main Sidebar area, and the sidebar will
 * disappear everywhere.
 *
 * @package Suits
 * @since Suits 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">

		<div id="content" class="site-content" role="main">

			<?php /* The loop */
			while ( have_posts() ) : the_post();
				get_template_part( 'content', 'page' );
				comments_template();
			endwhile; ?>

		</div><!-- #content .site-content -->

	</div><!-- #primary .content-area -->

<?php get_footer(); ?>

