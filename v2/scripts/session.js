$(document).ready(function() {
	$('.login').submit(function(e) {
		e.preventDefault();
		$.getJSON('../json/login.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
	
	$('.logout').click(function(e) {
	    $.getJSON('../json/logout.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});