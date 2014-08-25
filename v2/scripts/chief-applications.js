function viewApplication(id) {
	$(location).attr('href', 'index.php?page=application&id=' + id);
}

function removeApplication(id) {
	$.getJSON('../api/json/removeApplication.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}