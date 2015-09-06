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
	$('.chief-checklist-add').submit(function(e) {
		e.preventDefault();
		addNote(this);
	});

	$('.chief-checklist-edit').submit(function(e) {
		e.preventDefault();
		editNote(this);
	});
});

function addNote(form) {
	$.getJSON('../api/json/note/addNote.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function editNote(form) {
	$.getJSON('../api/json/note/editNote.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function removeNote(id) {
	$.getJSON('../api/json/note/removeNote.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}
