$(document).ready(function() {
	$('.edit-restricted-page-edit').submit(function(e) {
		e.preventDefault();
		
		$.getJSON('../api/json/restrictedpage/editRestrictedPage.php' + '?' + $(this).serialize(), function(data) {
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});