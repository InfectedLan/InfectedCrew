$(document).ready(function() {
	$('.event-screen-agenda-add').submit(function(e) {
		e.preventDefault();
		addAgenda(this);
	});
	
	$('.event-screen-agenda-edit').submit(function(e) {
		e.preventDefault();
		editAgenda(this);
	});
});

function addAgenda(form) {
	$.getJSON('../api/json/agenda/addAgenda.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message); 
		}
	});
}

function editAgenda(form) {
	$.getJSON('../api/json/agenda/editAgenda.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message); 
		}
	});
}

function removeAgenda(id) {
	$.getJSON('../api/json/agenda/removeAgenda.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}