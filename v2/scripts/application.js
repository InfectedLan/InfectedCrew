$(document).ready(function() {
	$('.chief-applications-reject').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/rejectApplication.php' + '?' + $(this).serialize(), function(data) {
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});

function acceptApplication(id) {
	$.getJSON('../api/json/acceptApplication.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}