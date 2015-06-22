/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2015 Infected <http://infected.no/>.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3.0 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 */

$(document).ready(function() {
	$('.chief-group-add').submit(function(e) {
		e.preventDefault();
		addGroup(this);
	});
	
	$('.chief-group-edit').submit(function(e) {
		e.preventDefault();
	    editGroup(this);
	});
	
	$('.chief-group-adduser').submit(function(e) {
		e.preventDefault();
		addUserToGroup(this);
	});
});

function viewGroup(groupId) {
	$(location).attr('href', '?page=chief-group&groupId=' + groupId);
}

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

function removeGroup(groupId) {
	$.getJSON('../api/json/group/removeGroup.php?id=' + groupId, function(data) {
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
}

function removeUserFromGroup(userId) {
	$.getJSON('../api/json/group/removeUserFromGroup.php?id=' + userId, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function removeUsersFromGroup(groupId) {
	$.getJSON('../api/json/group/removeUsersFromGroup.php?id=' + groupId, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function viewTeam(groupId) {
	$(location).attr('href', '?page=chief-team&groupId=' + groupId);
}