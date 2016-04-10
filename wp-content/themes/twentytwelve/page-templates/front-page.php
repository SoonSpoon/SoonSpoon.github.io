<?php



/**



 * Template Name: Front Page Template



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







get_header(); ?>



	<div id="primary" class="site-content-front">



		<div id="content" role="main">



			<?php while ( have_posts() ) : the_post(); ?>



				<?php if ( has_post_thumbnail() ) : ?>



					<div class="entry-page-image">



						<?php the_post_thumbnail(); ?>



					</div><!-- .entry-page-image -->

				<?php endif; ?>


				<?php get_template_part( 'content', 'page' ); ?>


			<?php endwhile; // end of the loop. 

			?>

			<table id="front-table" class="reservation">
				<?php get_template_part( 'inc/front', 'reservation' ); ?>
			</table>
			<div id='noReservationsMsg' style='display:none;text-align:center'><h1>All tables are filled! Sign up for notifications or check back soon for more last minute reservations.</h1></div>
		
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<script>

$(document).ready(function() {

	//refresh table every 10 seconds to make sure only unbooked reservations are showing
	
 	setInterval(refreshTable, 10000);

	function refreshTable(){
		$.post('/wp-admin/admin-ajax.php', { action: "update_front" }, function(response) {
			$('#front-table').html(response);
			if($("tbody").length == 0) {
				$("#noReservationsMsg").show();
			}else{
				$("#noReservationsMsg").hide();
			}
		});
	}

	if($("tbody").length == 0) {
		$("#noReservationsMsg").show();
	}

	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
		 $('#desktop-content').hide();
		 $('#mobile-content').show();

		 var listHtml = $('#restaurant-list').html();

		 $('#primary').append('<br><br><h4 style="text-align:center;line-height:20px; font-size:15px">We work with:</h4><br>' + '<p style="text-align: center; line-height:20px; font-size:15px">'+ listHtml +'</p>');
		 	
	}

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

