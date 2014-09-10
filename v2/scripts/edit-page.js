$(document).ready(function() {
	$('.edit-page').submit(function(e) {
		e.preventDefault();
		editPage(this);
	});
});

function editPage(form) {
	$.getJSON('../api/json/editPage.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			$(location).attr('href', 'index.php?page=functions-site-pages');
		} else {
			error(data.message); 
		}
	});
}