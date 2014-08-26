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

function removeGroup(groupId) {
	$.getJSON('../api/json/removeGroup.php?id=' + groupId, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function removeUserFromGroup(userId) {
	$.getJSON('../api/json/removeUserFromGroup.php?id=' + userId, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}