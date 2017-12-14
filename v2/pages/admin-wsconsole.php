<?php
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

require_once 'session.php';
require_once 'handlers/pagehandler.php';

$site = 'http://infected.no/v7/';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('admin.websocket')) {
	    echo '<h1>Websocket-konsoll</h1>';
	    echo '<script src="../api/scripts/websocket.js"></script>';
	    echo '<script src="scripts/admin-websocket-console.js"></script>';
	    echo '<div id="consoleArea">Vennligst vent...<br /></div>';
	    echo '<div id="inputArea"><input type="text" style="width: 100%;" placeholder="Skriv kommandoer her" /></div>';
	    
	} else {
		echo 'Du har ikke rettigheter til dette!';
	}
} else {
	echo 'Du er ikke logget inn!';
}
?>
