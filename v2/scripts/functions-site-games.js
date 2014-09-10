$(document).ready(function() {
	$('.functions-site-games-add').submit(function(e) {
		e.preventDefault();
	    addGame(this);
	});
	
	$('.functions-site-games-edit').submit(function(e) {
		e.preventDefault();
	    editGame(this);
	});
});

function addGame(form) {
	$.getJSON('../api/json/addGame.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message); 
		}
	});
}

function editGame(form) {
	$.getJSON('../api/json/editGame.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message); 
		}
	});
}

function removeGame(id) {
	$.getJSON('../api/json/removeGame.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function removeGameApplication(id) {
	$.getJSON('../api/json/removeGameApplication.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}