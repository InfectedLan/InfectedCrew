$(document).ready(function() {
	$('.restricted-edit-page-edit').submit(function(e) {
		e.preventDefault();
		
		$.getJSON('../api/json/editRestrictedPage.php' + '?' + $(this).serialize(), function(data) {
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});