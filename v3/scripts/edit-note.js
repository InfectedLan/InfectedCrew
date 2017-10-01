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
	validateSecondsOffset();

	$('.edit-note-secondsOffset').on('change', function() {
    validateSecondsOffset();
	});

	$('.edit-note').on('submit', function(event) {
		event.preventDefault();
		editNote(this);
	});
});

function validateSecondsOffset(value, time) {
	var value = $('.edit-note-secondsOffset').val();
	var time = $('.edit-note-time');

	if (value >= -86400 && value <= 172800) {
			time.prop('disabled', false);
	} else {
			time.prop('disabled', true);
	}
}

function editNote(form) {
	$.getJSON('../api/json/note/editNote.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			$(location).attr('href', 'index.php?page=event-checklist');
		} else {
			error(data.message);
		}
	});
}
