<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Suits
 * @since Suits 1.0
 */

get_header(); ?>

<div id="primary" class="content-area">
	<div id="content" class="site-content" role="main">

		<article id="post-0" class="error404 no-results not-found post">
			<header class="entry-header">
				<h1 class="entry-title"><?php _e( '404. That&rsquo;s an error.', 'suits' ); ?></h1>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<p><?php _e( 'It looks like nothing was found at this location. Perhaps searching can help.', 'suits' ); ?></p>
				<?php get_search_form(); ?>
			</div><!-- .entry-content -->
		</article><!-- #post-0 .error404 .no-results .no-found .post -->

	</div><!-- #content .site-content-->
</div><!-- #primary .content-area -->

<?php get_footer(); ?>