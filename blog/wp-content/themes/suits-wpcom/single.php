<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Suits
 * @since Suits 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">

		<div id="content" class="site-content" role="main">

			<?php /* The loop */
			while ( have_posts() ) : the_post();
				get_template_part( 'content', get_post_format() );
				suits_nav('nav-below');
				comments_template();
			endwhile; ?>

		</div><!-- #content .site-content -->

	</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

