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
	validateAddPrivate();
	validateAddSecondsOffset();
	//validateEditSecondsOffset();

	$('.edit-checklist-add-private').on('change', function() {
    validateAddPrivate();
	});

	$('.edit-checklist-add-secondsOffset').on('change', function() {
    validateAddSecondsOffset();
	});

	$('.edit-checklist-add').on('submit', function(event) {
		event.preventDefault();
		addNote(this);
	});

	$('.edit-checklist-edit-secondsOffset').on('change', function() {
    //validateEditSecondsOffset();
	});

	$('.edit-checklist-edit').on('submit', function(event) {
		event.preventDefault();
		editNote(this);
	});
});

function validateAddPrivate() {
	if ($('.edit-checklist-add-private').val() == '0') {
		$('.edit-checklist-add-teamId').prop('disabled', false);
		$('.edit-checklist-add-userId').prop('disabled', false);
	} else {
		$('.edit-checklist-add-teamId').prop('disabled', true);
		$('.edit-checklist-add-userId').prop('disabled', true);
	}

	$('.edit-checklist-add-userId').trigger("chosen:updated");
	$('.edit-checklist-add-teamId').trigger("chosen:updated");
}

function validateSecondsOffset(value, time) {
	if (value >= -86400 && value <= 172800) {
			time.prop('disabled', false);
	} else {
			time.prop('disabled', true);
	}
}

function validateAddSecondsOffset(value, time) {
	validateSecondsOffset($('.edit-checklist-add-secondsOffset').val(), $('.edit-checklist-add-time'));
}

function validateEditSecondsOffset() {
	validateSecondsOffset($('.edit-checklist-edit-secondsOffset').val(), $('.edit-checklist-edit-time'));
}

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
