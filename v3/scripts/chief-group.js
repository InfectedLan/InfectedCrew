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
	$('.chief-group-add').on('submit', function(event) {
		event.preventDefault();
		addGroup(this);
	});

	$('.chief-group-edit').on('submit', function(event) {
		event.preventDefault();
	    editGroup(this);
	});

	$('.chief-group-adduser').on('submit', function(event) {
		event.preventDefault();
		addUserToGroup(this);
	});
});

function viewGroup(id) {
	$(location).attr('href', '?page=chief-group&id=' + id);
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
}

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

function viewTeam(id) {
	$(location).attr('href', '?page=chief-team&id=' + id);
}