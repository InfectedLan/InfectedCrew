/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2017 Infected <http://infected.no/>.
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
	$('.admin-event-add').on('submit', function(event) {
		event.preventDefault();
		addEvent(this);
	});

	$('.admin-event-edit').on('submit', function(event) {
		event.preventDefault();
		editEvent(this);
	});
});

function addEvent(form) {
	$.getJSON('../api/json/event/addEvent.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function editEvent(form) {
	$.getJSON('../api/json/event/editEvent.php?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function removeEvent(eventId) {
	$.getJSON('../api/json/event/removeEvent.php?id=' + eventId, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function viewSeatmap(eventId) {
	$(location).attr('href', 'index.php?page=event-seatmap&id=' + eventId);
}

function copyMembers(eventId) {
	$.getJSON('../api/json/event/copyMembers.php?id=' + eventId, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}
