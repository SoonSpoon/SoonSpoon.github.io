<?php
/**
 * Video post format
 *
 * @package Suits
 * @since Suits 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php // If this is not the post page then add a link around all the content
	if ( !is_single() ) : ?>
		<a href="<?php the_permalink(); ?>" rel="bookmark">
	<?php endif; ?>

			<header class="entry-header">

				<?php if ( '' != get_the_post_thumbnail() && ! post_password_required() ) : ?>
					<div class="entry-thumbnail">
						<?php the_post_thumbnail(); ?>
					</div><!-- .entry-thumbnail -->
				<?php endif; ?>

				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

			</header><!-- .entry-header -->

	<?php if ( !is_single() ) : ?>
		</a>
	<?php endif; ?>

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php suits_entry_meta(); ?>

		<?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
			<?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a comment', 'suits' ) . '</span>', __( '1 Comment', 'suits' ), __( '% Comments', 'suits' ) ); ?>
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'suits' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->

</article><!-- #post-# .#post-class-# -->
