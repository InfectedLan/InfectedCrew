function viewApplication(id) {
	$(location).attr('href', 'index.php?page=application&id=' + id);
}

function queueApplication(id) {
	$.getJSON('../api/json/application/queueApplication.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function unqueueApplication(id) {
	$.getJSON('../api/json/application/unqueueApplication.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}