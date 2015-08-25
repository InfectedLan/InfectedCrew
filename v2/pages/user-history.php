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
require_once 'handlers/userhandler.php';
require_once 'handlers/userhistoryhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	$historyUser = isset($_GET['id']) ? UserHandler::getUser($_GET['id']) : Session::getCurrentUser();

	if ($user->hasPermission('*') ||
		$user->equals($historyUser)) {
		$eventList = UserHistoryHandler::getEventsByUser($historyUser);
		echo '<script src="scripts/userhistory.js"></script>';
		echo '<h3>Bruker historie</h3>';

		if (!empty($eventList)) {
			echo '<p>Denne brukeren har deltatt på følgende arrangementer:</p>';
			echo '<table>';
				echo '<tr>';
					echo '<th>Arrangement:</th>';
					echo '<th>Rolle:</th>';
				echo '</tr>';

				foreach ($eventList as $event) {
					echo '<tr>';
						echo '<td>' . $event->getTitle() . '</td>';
						echo '<td>' . $historyUser->getRoleByEvent($event) . '</td>';
					echo '</tr>';
				}
			echo '</table>';
		} else {
			echo '<p>Denne brukeren har ikke noe historie enda.</p>';
		}
	} else {
		echo 'Du har ikke rettigheter til dette.';
	}
} else {
	echo 'Du er ikke logget inn!';
}
?>
