$(document).ready(function() {
	$('.chief-teams-add').submit(function(e) {
		e.preventDefault();
		$.getJSON('../json/addTeam.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
	
	$('.chief-teams-edit').submit(function(e) {
		e.preventDefault();
	    $.getJSON('../json/editTeam.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
	
	$('.chief-teams-adduser').submit(function(e) {
		e.preventDefault();
		$.getJSON('../json/addUserToTeam.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});

function removeTeam(groupId, teamId) {
	$.getJSON('../json/removeTeam.php?groupId=' + groupId + '&teamId=' + teamId, function(data){
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function removeUserFromTeam(userId) {
	$.getJSON('../json/removeUserFromTeam.php?id=' + userId, function(data){
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}