$(document).ready(function() {
	$('.admin-events-add').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/event/addEvent.php' + '?' + $(this).serialize(), function(data) {
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
	
	$('.admin-events-edit').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/event/editEvent.php' + '?' + $(this).serialize(), function(data) {
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});

function viewSeatmap(id) {
	$(location).attr('href', 'index.php?page=event-seatmap&id=' + id);
}

function removeEvent(id) {
	$.getJSON('../api/json/event/removeEvent.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}