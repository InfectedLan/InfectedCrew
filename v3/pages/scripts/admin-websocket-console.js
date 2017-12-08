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
    console.log("Wsconsole says hi!");

    $(".inputArea").find("input").keypress({}, function(e) {
        if (e.which === 13) {
            var command = $(".inputArea").find("input").val();
            console.log('Sending message: ' + command);
            println(">" + command);
            Websocket.sendIntent("command", [command]);
            $(".inputArea").find("input").val("");
        }
    });

    //Start websocket
    Websocket.onOpen = onConnect;
    Websocket.onClose = onDisconnect;
    Websocket.onAuthenticate = onAuthenticate;
    Websocket.connect(Websocket.getDefaultConnectUrl());
    Websocket.addHandler("commandResponse", function(data) {
        for (var i = 0; i < data.length; i++) {
            println(data[i]);
        }
    });

    var onConnect = function() {
        println("Connected to websocket");
        Websocket.authenticate();
    };

    var onAuthenticate = function() {
        println("Authenticated - welcome to the admin console!");
    };

    var onDisconnect = function() {
        println("Lost connection to websocket...");
    };

    var println = function(text) {
        $(".consoleArea").append("<span>" + text + "</span><br />");
        $(".consoleArea").scrollTop($("#consoleArea")[0].scrollHeight);
    };
});