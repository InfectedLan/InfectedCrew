$(document).ready(function() {
	$('.edit-page-edit').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/editPage.php' + '?' + $(this).serialize(), function(data) {
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});