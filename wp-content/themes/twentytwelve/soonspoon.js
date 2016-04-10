
var isFlex = false;

$(document).ready(function() {

	var classList =$('body').attr('class').split(/\s+/);
	$.each( classList, function(index, item){
	    if (item.search('postid') != -1) {
	       arr = item.split("-");
	       postid = arr[1];
	       //console.log("post id is " +postid);
	    }
	});
	
	if($('body').hasClass('single-reservation')){
			checkStatusHandleHandlers();
			checkFlexSeating();
	}

	$('.reservation-form').find('form').find('input').on('click', function() {
		if($(this).attr('type') != 'submit') {
			checkStatusHandleHandlers();
		}
	});
	
	$('.reservation-form').find('form').find('.wpcf7-submit').on('click', function() {
		event.preventDefault(); //first stop the click from going any further
		event.stopPropagation();
		
		//now we have time to check post status
		$.get( "/wp-content/themes/twentytwelve/functions/check-post-status.php", {check_id: postid}, "json").done(function( data ) {
			//console.log("post status is " + data.post_status);
			  if(data.post_status != 'publish') { //If it's no good, we remove the form and display our loop
			  	$.get( "/wp-content/themes/twentytwelve/functions/booked-error-and-loop.php", function(data) {
			  		$('#content').html(data);
			  	});
			  }else{
			      //make sure user has selected seats
				  if(isFlex == true && $('select[name="seat_select"]').val()=='-1'){
						alert('You must fill in number of seats!');
						return false;
				  }
				  
			      //change the seat number hidden field val to the one selected by user
				  if(isFlex == true){
					  
					  var newSeats = $('select[name="seat_select"]').val();
					  var oldSubject = $('input[name="subject-line"]').val();
					  pattern = /(\d-\d)+/g;
					  var newSubject = oldSubject.replace(pattern,newSeats);
					  
					  $('input[name="subject-line"]').val(newSubject );
					  $('input[name="seats"]').val(newSeats);
				  }

				  $('.reservation-form').find('form').submit();
		      }
		});
	});	
});

function checkFlexSeating() {
	$.get( "/wp-content/themes/twentytwelve/functions/check-post-status.php", {check_id: postid}, "json").done(function( data ) {
		//check if its a flex seating type
		if(data.is_underbookable == '1') {
			 //seatsAvail comes back as something like '3-4'
			 var seatsAvail = data.seats_available; 
			 var seatsArr = seatsAvail.split("-");
			 var seatLow = seatsArr[0];
			 var seatHigh = seatsArr[1];
			 var seatOptionHtml = '<option value="-1">Please select...</option><option value="'+seatLow+'">'+seatLow+' seats</option><option value="'+seatHigh+'">'+seatHigh+' seats</option>';
			 var seatSelectHtml = '<br><br><p>Select Number of Seats<br><br><span class="wpcf7-form-control-wrap seat-select"><select name="seat_select">'+seatOptionHtml+'</select></span><p/>';
			 $('.subject-line').parent().append(seatSelectHtml);
			 
			 isFlex = true;
		}
	});
}

function checkStatusHandleHandlers() {
		//now we have time to check post status
		$.get( "/wp-content/themes/twentytwelve/functions/check-post-status.php", {check_id: postid}, "json").done(function( data ) {
			  if(data.post_status != 'publish') { //If it's no good, we remove the form and display our loop
			  	$.get( "/wp-content/themes/twentytwelve/functions/booked-error-and-loop.php", function(data) {
			  		$('#content').html(data);
			  	});
			  	
			  }
		});
}

