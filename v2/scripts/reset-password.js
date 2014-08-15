$(document).ready(function() {
	$('.request-reset-password').submit(function(e) {
		e.preventDefault();
	    $.post('../api/json/reset-password.php', $('.request-reset-password').serialize(), function(data) {
			if (data.result) {
	        	// TODO: Implement message to user here.
	        } else {
	         	error(data.message);
	        }
	    }, 'json');
	});
	$('.reset-password').submit(function(e) {
		e.preventDefault();
	    $.post('../api/json/reset-password.php?key=' + code, $('.reset-password').serialize(), function(data) {
			if (data.result) {
	        	// TODO: Implement message to user here.
	        } else {
	         	error(data.message);
	        }
	    }, 'json');
	});
});
