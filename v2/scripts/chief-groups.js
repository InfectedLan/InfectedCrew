$(document).ready(function() {
	$('.chief-groups-add').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/addGroup.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
	
	$('.chief-groups-edit').submit(function(e) {
		e.preventDefault();
	    $.getJSON('../api/json/changeGroup.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
	
	$('.chief-groups-adduser').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/addUserToGroup.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});

function removeGroup(groupId) {
	$.getJSON('../api/json/removeGroup.php?id=' + groupId, function(data){
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function removeUserFromGroup(userId) {
	$.getJSON('../api/json/removeUserFromGroup.php?id=' + userId, function(data){
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function updateSearchField(id) {
	//We have the id of the group, so we can get elements from that. \o/
	//Use this to ensure we get correct response back.dunno if needed
	updateKey = Math.random();
	$.getJSON('../api/json/searchusers.php?key=' + encodeURIComponent(updateKey) + "&query=" + encodeURIComponent( $('#userSearchBox' + id).val() ), function(data){
		if(data.result == true && data.key == updateKey)
		{
			$('#memberSelect' + id).empty();
			var userLength = data.users.length;
			if(userLength==0)
			{
				$('#memberSelect' + id).append('<option value="0" selected>Ingen</option>');
			}
			else
			{
				for(var i = 0; i < userLength; i++)
				{
					$('#memberSelect' + id).append('<option value="' + data.users[i].userId + '" ' + (i==0 ? 'selected' : '') + '>' + data.users[i].firstname + ' "' + data.users[i].nickname + '" ' + data.users[i].lastname + '</option>');
				}
			}
		}
  	});
}