$(document).ready(function() {
	$('.chief-groups-add').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/addGroup.php' + '?' + $(this).serialize(), function(data) {
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
	
	$('.chief-groups-edit').submit(function(e) {
		e.preventDefault();
	    $.getJSON('../api/json/editGroup.php' + '?' + $(this).serialize(), function(data) {
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
	
	$('.chief-groups-adduser').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/addUserToGroup.php' + '?' + $(this).serialize(), function(data) {
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});

function removeGroup(id) {
	$.getJSON('../api/json/removeGroup.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function removeUserFromGroup(id) {
	$.getJSON('../api/json/removeUserFromGroup.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function removeUsersFromGroup(id) {
	$.getJSON('../api/json/removeUsersFromGroup.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}