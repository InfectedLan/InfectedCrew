$(document).ready(function() {
	$('.edit-password').submit(function(e) {
		e.preventDefault();
		$.getJSON('../json/editUserPassword.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				$(location).attr('href', 'index.php?page=profile');
			} else {
				error(data.message); 
			}
		});
	});
});