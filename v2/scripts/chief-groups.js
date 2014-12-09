$(document).ready(function() {
	$('.chief-groups-add').submit(function(e) {
		e.preventDefault();
		addGroup(this);
	});
	
	$('.chief-groups-edit').submit(function(e) {
		e.preventDefault();
	    editGroup(this);
	});
	
	/*
	$('.chief-groups-edit').find('select[name=leader]').chosen().change(function(e) {
		editGroup(this.form);
	}); 
	*/
	
	$('.chief-groups-adduser').submit(function(e) {
		e.preventDefault();
		addUserToGroup(this);
	});
});

function addGroup(form) {
	$.getJSON('../api/json/group/addGroup.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message); 
		}
	});
}

function editGroup(form) {
	$.getJSON('../api/json/group/editGroup.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message); 
		}
	});
}

function removeGroup(id) {
	$.getJSON('../api/json/group/removeGroup.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function addUserToGroup(form) {
	$.getJSON('../api/json/group/addUserToGroup.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message); 
		}
	});
};

function removeUserFromGroup(id) {
	$.getJSON('../api/json/group/removeUserFromGroup.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function removeUsersFromGroup(id) {
	$.getJSON('../api/json/group/removeUsersFromGroup.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}