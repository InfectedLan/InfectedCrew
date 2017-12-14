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

$(function() {
    $("[data-table]").DataTable();
});

function viewApplication(id) {
	$(location).attr('href', '?page=application&id=' + id);
}

function queueApplication(applicationId) {
	$.post('../api/rest/group/application/queue.php', { applicationId: applicationId }, function(data) {
		if (data.result) {
			location.reload();
		} else {
			//error(data.message);
		}
	});
}

function unqueueApplication(applicationId) {
	$.post('../api/rest/group/application/unqueue.php', { applicationId: applicationId }, function(data) {
		if (data.result) {
			location.reload();
		} else {
			//error(data.message);
		}
	});
}