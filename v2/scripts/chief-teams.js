$(document).ready(function() {
	$('.chief-teams-add').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/team/addTeam.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
	
	$('.chief-teams-edit').submit(function(e) {
		e.preventDefault();
	    $.getJSON('../api/json/team/editTeam.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
	
	$('.chief-teams-adduser').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/team/addUserToTeam.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});

function removeTeam(groupId, teamId) {
	$.getJSON('../api/json/team/removeTeam.php?groupId=' + groupId + '&teamId=' + teamId, function(data){
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function removeUserFromTeam(id) {
	$.getJSON('../api/json/team/removeUserFromTeam.php?id=' + id, function(data){
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function removeUsersFromTeam(id) {
	$.getJSON('../api/json/team/removeUsersFromTeam.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}