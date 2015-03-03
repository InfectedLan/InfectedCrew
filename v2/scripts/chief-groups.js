/*
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2015 Infected <http://infected.no/>.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

$(document).ready(function() {
	$('.chief-groups-add').submit(function(e) {
		e.preventDefault();
		addGroup(this);
	});
	
	$('.chief-groups-edit').submit(function(e) {
		e.preventDefault();
	    editGroup(this);
	});
	
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