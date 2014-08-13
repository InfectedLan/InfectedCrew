$(document).ready(function() {
	$('.chief-applications-reject').submit(function(e) {
		e.preventDefault();
		$.getJSON('../json/rejectApplication.php' + '?' + $(this).serialize(), function(data) {
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});

function acceptApplication(id) {
	$.getJSON('../json/acceptApplication.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}