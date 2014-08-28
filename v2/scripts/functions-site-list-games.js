$(document).ready(function() {
	// $('.chief-groups-add').submit(function(e) {
		// e.preventDefault();
		// $.getJSON('../api/json/addGroup.php' + '?' + $(this).serialize(), function(data) {
			// if (data.result) {
				// location.reload();
			// } else {
				// error(data.message); 
			// }
		// });
	// });
	
	$('.functions-site-list-games').submit(function(e) {
		e.preventDefault();
	    $.getJSON('../api/json/editGame.php' + '?' + $(this).serialize(), function(data) {
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});

function removeGame(id) {
	$.getJSON('../api/json/removeGame.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}