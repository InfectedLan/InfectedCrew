$(document).ready(function() {
	$('.chief-teams-add').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/addTeam.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
	
	$('.chief-teams-edit').submit(function(e) {
		e.preventDefault();
	    $.getJSON('../api/json/editTeam.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
	
	$('.chief-teams-adduser').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/addUserToTeam.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});

function removeTeam(groupId, teamId) {
	$.getJSON('../api/json/removeTeam.php?groupId=' + groupId + '&teamId=' + teamId, function(data){
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function removeUserFromTeam(userId) {
	$.getJSON('../api/json/removeUserFromTeam.php?id=' + userId, function(data){
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}