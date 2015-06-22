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
	$('.chief-team-add').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/team/addTeam.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
	
	$('.chief-team-edit').submit(function(e) {
		e.preventDefault();
	    $.getJSON('../api/json/team/editTeam.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
	
	$('.chief-team-adduser').submit(function(e) {
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

function viewTeam(teamId) {
	$(location).attr('href', '?page=chief-team&teamId=' + teamId);
}

function removeTeam(groupId, teamId) {
	$.getJSON('../api/json/team/removeTeam.php?groupId=' + groupId + '&teamId=' + teamId, function(data){
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function removeUserFromTeam(userId) {
	$.getJSON('../api/json/team/removeUserFromTeam.php?userId=' + userId, function(data){
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function removeUsersFromTeam(teamId) {
	$.getJSON('../api/json/team/removeUsersFromTeam.php?teamId=' + teamId, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function viewGroup(groupId) {
	$(location).attr('href', '?page=chief-group&groupId=' + groupId);
}