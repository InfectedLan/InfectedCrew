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
	$('.chief-team-add').on('submit', function(event) {
		event.preventDefault();
		$.getJSON('../api/json/team/addTeam.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message);
			}
		});
	});

	$('.chief-team-edit').on('submit', function(event) {
		event.preventDefault();
	    $.getJSON('../api/json/team/editTeam.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message);
			}
		});
	});

	$('.chief-team-adduser').on('submit', function(event) {
		event.preventDefault();
		$.getJSON('../api/json/team/addUserToTeam.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message);
			}
		});
	});
});

function viewGroup(id) {
	$(location).attr('href', 'index.php?page=chief-team&groupId=' + id);
}

function removeTeam(id) {
	$.getJSON('../api/json/team/removeTeam.php?id=' + id, function(data){
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