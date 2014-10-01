$(document).ready(function() {
	$('.admin-events-add').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/addEvent.php' + '?' + $(this).serialize(), function(data) {
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
	
	$('.admin-events-edit').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/editEvent.php' + '?' + $(this).serialize(), function(data) {
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});

function viewSeatmap(id) {
	$(location).attr('href', 'index.php?page=functions-seatmap&id=' + id);
}

function removeEvent(id) {
	$.getJSON('../api/json/removeEvent.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}