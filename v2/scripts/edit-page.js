$(document).ready(function() {
	$('.edit-page-edit').submit(function(e) {
		e.preventDefault();
		CKEDITOR.instances.ckeditor.updateElement();
		
		$.getJSON('../api/json/editPage.php' + '?' + $(this).serialize(), function(data) {
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});