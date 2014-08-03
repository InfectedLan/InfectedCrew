$(document).ready(function() {
	$('.edit-profile').submit(function(e) {
		e.preventDefault();
	    $.post('../json/edit-profile.php', $('.edit-profile').serialize(), function(data) {
			if (data.result) {
				$(location).attr('href', 'index.php?page=profile');
				
	        	info("Din bruker har blitt laget! Sjekk e-posten din for å aktivere, før du logger inn.");
	        } else {
	         	error(data.message);
	        }
	    }, 'json');
	});
});