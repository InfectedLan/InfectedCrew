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
	$('.compo-add').submit(function(e) {
		e.preventDefault();
		addCompo(this);
	});

	$('.compo-edit').submit(function(e) {
		e.preventDefault();
		editCompo(this);
	});
});

function addCompo(form) {
	$.getJSON('../api/json/compo/addCompo.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			window.location.replace("index.php?page=compo-view&id=" + data.id);
		} else {
			error(data.message);
		}
	});
}

function editCompo(form) {
	$.getJSON('../api/json/compo/editCompo.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}
