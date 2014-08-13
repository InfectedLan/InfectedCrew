$(document).ready(function() {
	$('.chief-groups-add').submit(function(e) {
		e.preventDefault();
		$.getJSON('../json/addGroup.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
	
	$('.chief-groups-edit').submit(function(e) {
		e.preventDefault();
	    $.getJSON('../json/changeGroup.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
	
	$('.chief-groups-adduser').submit(function(e) {
		e.preventDefault();
		$.getJSON('../json/addUserToGroup.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});

function removeGroup(groupId) {
	$.getJSON('../json/removeGroup.php?id=' + groupId, function(data){
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function removeUserFromGroup(userId) {
	$.getJSON('../json/removeUserFromGroup.php?id=' + userId, function(data){
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}