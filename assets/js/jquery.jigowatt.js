jQuery(document).ready(function(){

	$('#reservationform').submit(function(){

		var action = $(this).attr('action');

		$("#message").slideUp(750,function() {
		$('#message').hide();

 		$('#submit')
			.after('<img src="images/ajax-loader.gif" class="loader" />')
			.attr('disabled','disabled');

		$.post(action, {
			location: $('#location').val(),
			room: $('#room').val(),
			checkin: $('#checkin').val(),			
			checkout: $('#checkout').val(),
			adults: $('#adults').val(),
			children: $('#children').val(),
		},
			function(data){
				document.getElementById('message').innerHTML = data;
			
				if(data.match('success') != null) 
				{
					location.href = './search.php';
					//$('#reservationform .form-group, #reservationform .btn').slideUp('slow');
				}
				else{
						$('#message').slideDown('slow');
						$('#reservationform img.loader').fadeOut('slow',function(){$(this).remove()});
					//location.href = './search.php';
						
				}
				
				$('#submit').removeAttr('disabled');
				
			}
		);

		});

		return false;

	});
	
	$('#contactform').submit(function(){

		var action = $(this).attr('action');

		$("#message").slideUp(750,function() {
		$('#message').hide();

 		$('#submit')
			.after('<img src="assets/ajax-loader.gif" class="loader" />')
			.attr('disabled','disabled');

		$.post(action, {
			name: $('#name').val(),
			email: $('#email').val(),
			subject: $('#subject').val(),
			comments: $('#comments').val(),
			verify: $('#verify').val()
		},
			function(data){
				document.getElementById('message').innerHTML = data;
				$('#message').slideDown('slow');
				$('#contactform img.loader').fadeOut('slow',function(){$(this).remove()});
				$('#submit').removeAttr('disabled');
				if(data.match('success') != null) $('#contactform .form-group, #contactform .btn').slideUp('slow');

			}
		);

		});

		return false;

	});

});