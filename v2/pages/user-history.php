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

	if ($user->hasPermission('user.history') ||
		$user->equals($historyUser)) {
		$eventList = $historyUser->getParticipatedEvents($historyUser);

		echo '<h3>Du ser nå ' . $historyUser->getDisplayName() . '\'s historikk</h3>';

		if (!empty($eventList)) {
			echo '<p>Denne brukeren har deltatt på følgende arrangementer:</p>';
			echo '<table>';
				echo '<tr>';
					echo '<th>Arrangement:</th>';
					echo '<th>Rolle:</th>';
					echo '<th>Medlemskap:</th>';
					echo '<th>Billetter:</th>';
				echo '</tr>';

				foreach ($eventList as $event) {
					echo '<tr>';
						echo '<td>' . $event->getTitle() . '</td>';
						echo '<td>' . $historyUser->getRole($event) . '</td>';

						if ($historyUser->isGroupMember($event)) {
							$group = $historyUser->getGroup($event);

							echo '<td><a href="index.php?page=all-crew&id=' . $group->getId() . '">' . $group->getTitle() . '</a></td>';
							echo '<td>Ingen</td>';
						} else if ($historyUser->hasTicket($event)) {
							echo '<td>Ingen</td>';
							echo '<td>';
								$ticketList = $historyUser->getTickets($event);

								foreach ($ticketList as $ticket) {
									echo '<a href="index.php?page=ticket&id=' . $ticket->getId() . '">#' . $ticket->getId() . '</a>';

									// Only print comma if this is not the last ticket in the array.
									echo $ticket !== end($ticketList) ? ', ' : null;
								}
							echo '</td>';
						}
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
