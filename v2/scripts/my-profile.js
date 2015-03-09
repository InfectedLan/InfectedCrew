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
	$('.my-profile-group-add-user').submit(function(e) {
		e.preventDefault();
		addGroupUserToGroup(this);
	});

});

function addUserToGroup(form) {
	$.getJSON('../api/json/group/addUserToGroup.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message); 
		}
	});
};

var seatHandlerFunction = function(identifyer, seatDivId, taken, takenData) {
	if (!taken) {
		return "free";
	}

	if (takenData.id == ticketId) {
		return "current";
	}

	return "taken";
}

var callback = function() {
	for (var i = 0; i < seatmapData.rows.length; i++) {
		for(var s = 0; s < seatmapData.rows[i].seats.length; s++) {
			if (!seatmapData.rows[i].seats[s].occupied) {
				$("#seat" + seatmapData.rows[i].seats[s].id).click({seatId: seatmapData.rows[i].seats[s].id}, function(e) {
					updateSeat(e.data.seatId);
				});
			}
		}
	}
}

function updateSeat(seatId) {
	$.getJSON("../api/json/ticket/seatTicket.php?ticket=" + ticketId + "&seat="+seatId, function(data){
		if(data.result)
		{
			//downloadAndRenderSeatmap("#seatmapCanvas", seatHandlerFunction, callback);
			location.reload();
		}
		else
		{
			error(data.message);
		}
  	});
}