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
	$('.slidingBox .details').hide();

	$('.slidingBox .show_hide').on('click', function() {
		$(this).text($(this).next('.details').is(':visible') ? 'Vis' : 'Skjul');

		$(this).next('.details').slideToggle();
	});

	$(this).on('change', 'input:checkbox', function() {
		$('.event-checklist-check').trigger('submit');
	})

	$('.event-checklist-check').on('submit', function(event) {
	    event.preventDefault();
	    checkNote(this);
	});

	validatePrivate();
	validateSecondsOffset();

	$('.event-checklist-add-private').on('change', function() {
    validatePrivate();
	});

	$('.event-checklist-add-secondsOffset').on('change', function() {
    validateSecondsOffset();
	});

	$('.event-checklist-add').on('submit', function(event) {
		event.preventDefault();
		addNote(this);
	});
});

function checkNote(form) {
	$.getJSON('../api/json/note/editNoteDone.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function editNote(id) {
	$(location).attr('href', 'index.php?page=edit-note&id=' + id);
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

function validatePrivate() {
	if ($('.event-checklist-add-private').val() == '0') {
		$('.event-checklist-add-teamId').prop('disabled', false);
		$('.event-checklist-add-userId').prop('disabled', false);
	} else {
		$('.event-checklist-add-teamId').prop('disabled', true);
		$('.event-checklist-add-userId').prop('disabled', true);
	}

	$('.event-checklist-add-userId').trigger("chosen:updated");
	$('.event-checklist-add-teamId').trigger("chosen:updated");
}

function validateSecondsOffset(value, time) {
	var value = $('.event-checklist-add-secondsOffset').val();
	var time = $('.event-checklist-add-time');

	if (value >= -86400 && value <= 172800) {
			time.prop('disabled', false);
	} else {
			time.prop('disabled', true);
	}
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
