$(document).ready(function() {
	$('.login').submit(function(e) {
		e.preventDefault();
	    $.post( '../json/login.php', $('.login').serialize(), function(data) {
	    	console.log('Got data!');
	        
			if (data.result) {
				location.reload();
			} else {
				error(data.message);
			}
		}, 'json');
	});
});

$(document).ready(function() {
	$('.logout').click(function(e) {
	    $.post( '../json/logout.php', $('.logout').serialize(), function(data) {
	    	console.log('Got data!');
	        
			if (data.result) {
				location.reload();
			} else {
				error(data.message);
			}
		}, 'json');
	});
});