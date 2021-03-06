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
	$('.user-profile-group-add-user').on('submit', function(event) {
		event.preventDefault();
		addUserToGroup(this);
	});

    $('.edit-user-note').on('submit', function(event) {
        event.preventDefault();
        editUserNote(this);
    });

    $('.bong-submit').on('submit', function(event) {
        event.preventDefault();
        transactBong(this);
    });
    console.log("added bong submit thing");
});

function transactBong(form) {
    $.post("../api/rest/nfc/bong/transaction/create.php", $(form).serialize(), function(result){
        if(result.result) {
            location.reload();
        } else {
            error(result.message);
        }
    });
}

function setUserCurfew(userid, flag) {
    $.post("../api/rest/user/curfew/set.php", "id=" + userid + "&curfew=" + flag, function(result){
        location.reload();
    	if(result.result) {

        } else {
            error(result.message);
        }
    });
}

function editUserNote(form) {
 	$.getJSON('../api/json/user/editUserNote.php' + '?' + $(form).serialize(), function(data) {
 		if (data.result) {
      location.reload();
 		} else {
 			error(data.message);
 		}
 	});
}

function addUserToGroup(form) {
	$.getJSON('../api/json/group/addUserToGroup.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function activateUser(id) {
	$.getJSON('../api/json/user/activateUser.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}
