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
	$('.admin-website-add').submit(function(e) {
		e.preventDefault();
		addPage(this);
	});
});

function addPage(form) {
	$.getJSON('../api/json/page/addPage.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message); 
		}
	});
}

function editPage(id) {
	$(location).attr('href', '?page=edit-page&id=' + id);
}

function removePage(id) {
	$.getJSON('../api/json/page/removePage.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}