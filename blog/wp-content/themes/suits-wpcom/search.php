<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Suits
 * @since Suits 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">

		<div id="content" class="site-content" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'suits' ), get_search_query() ); ?></h1>
			</header><!-- .page-header -->

			<?php /* The loop */
			while ( have_posts() ) : the_post();
				get_template_part( 'content', get_post_format() );
			endwhile;

			suits_nav('nav-below');

		 	else :
		 		get_template_part( 'content', 'none' );
			endif;?>

		</div><!-- #content .site-content -->

	</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>