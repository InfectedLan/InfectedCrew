$(document).ready(function() {
	$('.edit-permissions').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/addUserPermission.php' + '?' + $(this).serialize(), function(data) {
			if (data.result) {
				$(location).attr('href', 'index.php?page=profile&id=' + $('input[name="id"]').val());
			} else {
				error(data.message); 
			}
		});
	});
});