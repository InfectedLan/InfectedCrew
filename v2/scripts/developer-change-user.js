$(document).ready(function() {
	$('.developer-changeuser').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/changeToUser.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				$(location).attr('href', 'index.php?page=my-profile');
			} else {
				error(data.message); 
			}
		});
	});
});