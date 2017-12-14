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
    $('input[type="checkbox"]').iCheck({
        checkboxClass: 'icheckbox_square-blue'
    });

    $('.admin-permission-create').on('submit', function(event) {
        event.preventDefault();
        createPermission(this);
    });
});

function createPermission(form) {
    $.post('../api/rest/user/permission/create.php', $(form).serialize(), function(data) {
        if (data.result) {
            location.reload();
        }
    });
}

function removePermission(userId, permissionId) {
    $.get('../api/rest/user/permission/delete.php?userId=' + userId + '&permissionId=' + permissionId, function(data) {
        if (data.result) {
            location.reload();
        }
    });
}

function removePermissions(userId) {
    $.get('../api/rest/user/permission/delete.php?userId=' + userId, function(data) {
        if (data.result) {
            location.reload();
        }
    });
}

function redirectToUser(userId) {
    $(location).attr('href', 'index.php?page=admin-permission&userId=' + userId);
}