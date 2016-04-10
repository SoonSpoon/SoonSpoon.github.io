<?php

/**
 * Template Name: Restaraunt Dashboard - Add and Cancel
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
	<style>
		.underbooked-line td{line-height: 25px;}
		.underbooked-line input{width:25px;}
	
	</style>
	
	<div id="primary" class="site-content-front">
		<a id ="back" href="<?php echo home_url().'/booked-listing';?>" title="Logout">View Booked</a>
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

		
			<form id="reservation-form">
				<?php get_template_part( 'inc/reservation', 'table' ); ?>
		 	</form>
		
			
			<!--About Flex Seating Modal -->
			
			<div class="modal fade" id="flexHelpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        <h4 class="modal-title" id="myModalLabel">Flexible Seating</h4>
			      </div>
			      <div class="modal-body">
			        <p>When checked, that reservation will display on the front page for diners as a range of the number of seats to number of seats minus one. For example, a 4 top will appear as 3-4 seats to the diner.</p>
					<p>When a diner reserves a "Flex" type reservation, they will be prompted to choose the final number of seats they'd like. In this example, they'd select from 3 or 4 seats.</p>
					<p>Leave this unchecked for your table to display the # seats as normal.</p>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			      </div>
			    </div>
			  </div>
			</div>
		
	</div><!-- #primary -->

<script type="text/javascript" >

jQuery(document).ready(function($) {

	//Get today and tommorrow's dates to set default values in the reservation add row date inputs.
	<?php date_default_timezone_set('America/New_York'); ?>
	var today = '<?php echo $today = date("Y-m-d");?>';
	var tomm = '<?php echo $tomorrow = date("Y-m-d",strtotime($today . "+1 days")); ?>';

	//build out the time select html for the reservation add row 
    var time_select_html = '<select required name="time" id="time"><option value="07:00">7:00 AM</option><option value="07:15">7:15 AM</option><option value="07:30">7:30 AM</option><option value="07:45">7:45 AM</option><option value="08:00">8:00 AM</option><option value="08:15">8:15 AM</option><option value="08:30">8:30 AM</option><option value="08:45">8:45 AM</option><option value="09:00">9:00 AM</option><option value="09:15">9:15 AM</option><option value="09:30">9:30 AM</option><option value="09:45">9:45 AM</option><option value="10:00">10:00 AM</option><option value="10:15">10:15 AM</option><option value="10:30">10:30 AM</option><option value="10:45">10:45 AM</option><option value="11:00">11:00 AM</option><option value="11:15">11:15 AM</option><option value="11:30">11:30 AM</option><option value="11:45">11:45 AM</option><option value="12:00">12:00 PM</option><option value="12:15">12:15 PM</option><option value="12:30">12:30 PM</option><option value="12:45">12:45 PM</option><option value="13:00">1:00 PM</option><option value="13:15">1:15 PM</option><option value="13:30">1:30 PM</option><option value="13:45">1:45 PM</option><option value="14:00">2:00 PM</option><option value="14:15">2:15 PM</option><option value="14:30">2:30 PM</option><option value="14:45">2:45 PM</option><option value="15:00">3:00 PM</option><option value="15:15">3:15 PM</option><option value="15:30">3:30 PM</option><option value="15:45">3:45 PM</option><option value="16:00">4:00 PM</option><option value="16:15">4:15 PM</option><option value="16:30">4:30 PM</option><option value="16:45">4:45 PM</option><option value="17:00">5:00 PM</option><option value="17:15">5:15 PM</option><option value="17:30">5:30 PM</option><option value="17:45">5:45 PM</option><option selected="selected" value="18:00">6:00 PM</option><option value="18:15">6:15 PM</option><option value="18:30">6:30 PM</option><option value="18:45">6:45 PM</option><option value="19:00">7:00 PM</option><option value="19:15">7:15 PM</option><option value="19:30">7:30 PM</option><option value="19:45">7:45 PM</option><option value="20:00">8:00 PM</option><option value="20:15">8:15 PM</option><option value="20:30">8:30 PM</option><option value="20:45">8:45 PM</option><option value="21:00">9:00 PM</option><option value="21:15">9:15 PM</option><option value="21:30">9:30 PM</option><option value="21:45">9:45 PM</option><option value="22:00">10:00 PM</option><option value="22:15">10:15 PM</option><option value="22:30">10:30 PM</option><option value="22:45">10:45 PM</option><option value="23:00">11:00 PM</option><option value="23:15">11:15 PM</option><option value="23:30">11:30 PM</option><option value="23:45">11:45 PM</option></select>';

    //add new row to the reservation table and then hide the add button until the current row is cancelled or added
	$('#reservation-form').on('click','#add-reservation',function(){

		date_input_html = '<td><select required class="date-selector"><option value="'+today+'">Today</option><option value="'+tomm+'">Tommorrow</option><option value="other">Other</option></select></td>';

		datepicker_row = '<tr id="datepicker-row" style="display:none"><td colspan="4"> <input id="datePicker" type="date" min="'+today+'" value="'+today+'" class="date" style="display:none" placeholder="date" name="date"/><td><tr>';

		html = '<tr class="reservation-line"><td><input class="seats-input" placeholder="# seats" type="number" min="1" name="seats" required /><td><input class="flex-input" type="checkbox" value="1" name="flex" checked required /></td></td>'+date_input_html+'</td><td>'+time_select_html+'</td><td>&nbsp;<a class="brown-block remove-row">Cancel</a>&nbsp;/&nbsp;<a class="brown-block add-reservation">Add</a></td></tr>'+ datepicker_row;
		
		//html = '<tr class="reservation-line"><td><input class="seats-input" placeholder="# seats" type="number" min="1" name="seats" required /><td>&nbsp;&#9660;&nbsp;</td></td>'+date_input_html+'</td><td>'+time_select_html+'</td><td>&nbsp;<a class="brown-block remove-row">Cancel</a>&nbsp;/&nbsp;<a class="brown-block add-reservation">Add</a></td></tr>'+ datepicker_row;
		//html += '<tr class="underbooked-line"><td colspan="3"><span>Underbooked?</span><input class="flex-input" checked type="checkbox" value="1" name="flex" required /></td></tr>'
		
		$('.reservation').append(html);

		$('#add-reservation').hide();
	});

    //allow user to cancel out of adding a new row if they change thier mind, also show add reservation button again
	$('#reservation-form').on('click','.remove-row',function(){
			$(this).parent().parent().remove();
			$('#datePicker').remove();
			$('#add-reservation').show();
		});


    //set the value of the date input for the new reservation based on the value selected, if other is chosen, show hidden row with the calendar widget
	$('#reservation-form').on('change','.date-selector',function(){

		if($(this).val() =='other'){
			$(this).parent().parent().next().show().find('.date').show().focus();
		}

		if($(this).val() == today){
		
			$(this).parent().parent().next().hide().find('.date').val(today).hide();
		}

		if($(this).val() == tomm){
			$(this).parent().parent().next().hide().find('.date').val(tomm).hide();
		}
		
	});

	// make an ajax call to the cancel reservation ajax handler defined in setfive_dev.php with the setfive plugin folder
	$('#reservation-form').on('click','.cancel',function(){
		//warn user first
		var answer = confirm("Are you sure you want to cancel this reservation?");

		//only proceed if user is cool with it
		if(answer){
			var id = $(this).attr("data-id");
			$.post('/wp-admin/admin-ajax.php', {action:'cancel_reservation',cancel_id:id}, function(response) {
				$('#reservation-form').html(response);
			});
		}
		
	});

	// make an ajax call to the update reservation ajax handler defined in setfive_dev.php with the setfive plugin folder
	$('#reservation-form').on('click','.add-reservation',function(e){

		//allow another click only after waiting 2 seconds (prevent double clicks)
		$(e.target).click(do_nothing); 
		  setTimeout(function(){
		    $(e.target).unbind('click', do_nothing);
		  }, 2000); 

		//validate against empty number or negative number of seats, other date and time fields should always have valid values
		var validated = true;

		if($(this).parent().parent().find('.seats-input').val()==''){
			alert('Please fill out # out seats before adding the reservation.');
			validated = false;
			return false;
		}

		if($(this).parent().parent().find('.seats-input').val() < 1){
			alert('Please enter a positive number of seats for the reservation.');
			validated = false;
			return false;
		}

		if($(this).parent().parent().find('.seats-input').val() == 1 && $('input[name="flex"]').prop("checked")==true ){
			alert('You cannot use flex seating on a single seat reservation.');
			validated = false;
			return false;
		}

		//only procceed if row fields are valid, grab field values and make ajax call
		if(validated){		
			var time = $(this).parent().parent().find('select[name="time"]').val();
			var seats = $(this).parent().parent().find('input[name="seats"]').val();
			var date = $(this).parent().parent().next().find('#datePicker').val();
			var flex = '0';
			
			if($('input[name="flex"]').prop("checked")==true)
				flex = '1';

 			$.post('/wp-admin/admin-ajax.php', {action:'update_reservation',time:time, seats: seats, date: date,flex: flex}, function(response) {
				$('#reservation-form').html(response);
				$('#reservation-confirmation').fadeIn(400).delay(6000).fadeOut(400);
				$('#add-reservation').show();
			}); 

		}else
				return false;
	});

	//help function to prevent double clicks
	function do_nothing() { 
		  return false;
		}

	//prevent user from typing non numbers into seats field
	$("#reservation-form").on('keydown','.seats-input',function (event) {
        if (event.shiftKey) {
            event.preventDefault();
        }

        if (event.keyCode == 46 || event.keyCode == 8) {
        }
        else {
            if (event.keyCode < 95) {
                if (event.keyCode < 48 || event.keyCode > 57) {
                    event.preventDefault();
                }
            }
            else {
                if (event.keyCode < 96 || event.keyCode > 105) {
                    event.preventDefault();
                }
            }
        }
    });

	
	$('#flex a').on('click',function(){

		$('#flexHelpModal').modal('show');

		return false;
		
	});

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

