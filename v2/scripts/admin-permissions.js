$(document).ready(function() {
	$('.admin-permissions-add').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/addPermission.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});