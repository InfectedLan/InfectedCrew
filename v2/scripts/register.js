$(document).ready(function() {
	$('.register').submit( function(e) {
		e.preventDefault();
	    $.post( '../json/register.php', $('.register').serialize(), function(data) {
	    	console.log('Got data!');
	        
			if (data.result) {
	        	//$('#registerForm').reset();
	        	showLoginBoxFromRegister();
	        	info("Din bruker har blitt laget! Sjekk e-posten din for å aktivere, før du logger inn.");

	        } else {
	         	error(data.message);
	        }
	    }, 'json');
	});
	
	$('.postalcode').change(function() {
		$.getJSON('../json/citydictionary.php?postalcode=' + encodeURIComponent($('.postalcode').val()), function(data) {
			if (data.result) {
				$('.city').text(data.message);
			}
		});
	});
});