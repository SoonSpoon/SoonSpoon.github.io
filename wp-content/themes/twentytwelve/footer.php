<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
	</div><!-- #main .wrapper -->
	<footer id="colophon" role="contentinfo">
		<div class="site-info">
			<?php do_action( 'twentytwelve_credits' ); ?>
			
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->


<?php wp_footer(); ?>
</body>

<a class="brown-block" style="float:left;margin-bottom:10px;margin-right:10px;margin-top:20px" href="http://www.soonspoon.com/terms-of-use/">Terms of Use</a><a class="brown-block" style="float:left;margin-bottom:10px;margin-top:20px" href="http://www.soonspoon.com/privacy-policy/">Privacy Policy</a>

<a class="brown-block" style="float:right;margin-bottom:10px;margin-top:20px" href="<?php echo wp_login_url(); ?>">Login</a>

</html>

