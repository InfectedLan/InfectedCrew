$(document).ready(function() {
	$('.application').submit(function(e) {
		e.preventDefault();
	    $.post('../json/application.php', $('.application').serialize(), function(data) {
			if (data.result) {
	        	info(data.message);
	        } else {
	         	error(data.message);
	        }
	    }, 'json');
	});
});