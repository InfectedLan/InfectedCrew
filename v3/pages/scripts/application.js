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
    $('.application-create').on('submit', function(event) {
        event.preventDefault();
        createApplication(this);
    });

    $('.application-reject').on('submit', function(event) {
        event.preventDefault();
        rejectApplication(this);
    });
});

function createApplication(form) {
    $.post('../api/rest/group/application/create.php', $(form).serialize(), function(data) {
        if (data.result) {
            info(data.message);
        } else {
            //error(data.message);
        }
    });
}

function acceptApplication(applicationId) {
	$.post('../api/rest/group/application/accept.php', { applicationId: applicationId }, function(data) {
		if (data.result) {
			location.reload();
		} else {
            //error(data.message);
		}
	});
}

function rejectApplication(form) {
    $.post('../api/rest/group/application/reject.php', $(form).serialize(), function(data) {
        if (data.result) {
            location.reload();
        } else {
            //error(data.message);
        }
    });
}