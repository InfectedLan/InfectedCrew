$(document).ready(function() {
	$('.chief-applications-reject').submit(function(e) {
		e.preventDefault();

		$.getJSON('../api/json/application/rejectApplication.php' + '?' + $(this).serialize(), function(data) {
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});

function acceptApplication(id) {
	$.getJSON('../api/json/application/acceptApplication.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}