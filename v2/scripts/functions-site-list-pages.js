$(document).ready(function() {
	$('.functions-site-list-pages-add').submit(function(e) {
		e.preventDefault();
		addPage(this);
	});
});

function addPage(form) {
	$.getJSON('../api/json/addPage.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message); 
		}
	});
}

function editPage(id) {
	$(location).attr('href', 'index.php?page=edit-page&id=' + id);
}

function removePage(id) {
	$.getJSON('../api/json/removePage.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}