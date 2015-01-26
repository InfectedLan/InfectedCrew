$(document).ready(function() {
	$('.chief-my-crew-add').submit(function(e) {
		e.preventDefault();
		addPage(this);
	});
});

function addPage(form) {
	$.getJSON('../api/json/restrictedpage/addRestrictedPage.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message); 
		}
	});
}

function editPage(id) {
	$(location).attr('href', 'index.php?page=edit-restricted-page&id=' + id);
}

function removePage(id) {
	$.getJSON('../api/json/restrictedpage/removeRestrictedPage.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}