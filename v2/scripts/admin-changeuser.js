$(document).ready(function() {
	$('.admin-changeuser').submit(function(e) {
		e.preventDefault();
		$.getJSON('../json/changeToUser.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				$(location).attr('href', 'index.php?page=profile');
			} else {
				error(data.message); 
			}
		});
	});
});