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
    $('.event-checkin-fetch').on('submit', function(event) {
        event.preventDefault();
        fetchTicket();
    });
});

function fetchTicket() {
    var ticketId = $("input[name='ticketId']").val();
    var content = null;

    // Remove old entries.
    $(".ticket-details").empty();

    $.get('../api/rest/ticket/user/fetch.php?ticketId=' + ticketId, function(data) {
		if (data.result) {
            var user = data.userData;
            var birthdate = new Date(user.birthdate * 1000);
            var registereddate = new Date(user.registereddate * 1000);

			// Append with our new entries.
            content = '<table class="table">' +
                          '<tr>' +
                              '<th>Navn</th>' +
                              '<td>' + user.firstname + ' ' + user.lastname +'</td>' +
                          '</tr>' +
                          '<tr>' +
                              '<th>Brukernavn</th>' +
                              '<td>' + user.username + '</td>' +
                          '</tr>' +
                          '<tr>' +
                              '<th>E-post</th>' +
                              '<td>' + user.email + '</td>' +
                          '</tr>' +
                          '<tr>' +
                              '<th>Født</th>' +
                              '<td>' + birthdate.getDate() + '.' + birthdate.getMonth() + 1 + '.' + birthdate.getFullYear() + '</td>' +
                          '</tr>' +
                          '<tr>' +
                              '<th>Kjønn</th>' +
                              '<td>' + user.gender + '</td>' +
                          '</tr>' +
                          '<tr>' +
                              '<th>Telefon</th>' +
                              '<td>' + user.phone + '</td>' +
                          '</tr>' +
                          '<tr>' +
                              '<th>Adresse</th>' +
                              '<td>' + user.address + '<br>' + user.postalcode + ', ' + user.city + '</td>' +
                          '</tr>' +
                          '<tr>' +
                              '<th>Dato registrert</th>' +
                              '<td>' + registereddate.getDate() + '.' + registereddate.getMonth() + '.' + registereddate.getFullYear() + '</td>' +
                          '</tr>' +
                          '<tr>' +
                              '<th>Alder</th>' +
                              '<td>' + user.age + ' År</td>' +
                          '</tr>' +
                      '</table>' +
                      '<button type="submit" class="btn btn-primary" onClick="checkInTicket(' + ticketId + ')">Godkjenn</button>';
		} else {
            content = data.message;
        }

        $(".ticket-details").html(content);
    });
}

function checkInTicket(ticketId) {
    $.get('../api/rest/ticket/checkIn.php?ticketId=' + ticketId, function(data) {
        if (data.result) {
            // Remove old entries.
            $(".ticket-details").empty();
        }
    });
}
