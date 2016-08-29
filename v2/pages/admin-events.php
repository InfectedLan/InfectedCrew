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
require_once 'handlers/eventhandler.php';
require_once 'handlers/locationhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('admin.events')) {
		echo '<script src="scripts/admin-events.js"></script>';
		echo '<h3>Arrangementer:</h3>';
		echo '<p>Her er en liste over Infected arrangementer som har vært eller skal være. Neste arrangement blir automatisk vist på hovedsiden.</p>';

		echo '<table>';
			echo '<tr>';
				echo '<th>Id:</th>';
				echo '<th>Navn:</th>';
				echo '<th>Sted/Deltakere:</th>';
				echo '<th>Booking:</th>';
				echo '<th>Prioritets-seating:</th>';
				echo '<th>Vanelig seating:</th>';
				echo '<th>Start:</th>';
				echo '<th>Slutt:</th>';
			echo '</tr>';

			foreach (EventHandler::getEvents() as $event) {
				echo '<tr>';
					echo '<form class="admin-events-edit" name="input" method="post">';
						echo '<input type="hidden" name="id" value="' . $event->getId() . '">';
						echo '<td>' . $event->getId() . '</td>';
						echo '<td>' . $event->getTitle() . '</td>';
						echo '<td>';
							echo '<select class="chosen-select" name="location" required>';
								echo '<option value="' . $event->getLocation()->getId() . '">' . $event->getLocation()->getTitle() . '</option>';
							echo '</select>';
							echo '<input type="number" name="participants" value="' . $event->getParticipants() . '" required>';
						echo '</td>';
						echo '<td>';
							echo '<input type="date" name="bookingDate" value="' . date('Y-m-d', $event->getBookingTime()) . '" required>';
							echo '<input type="time" name="bookingTime" value="' . date('H:i', $event->getBookingTime()) . '" required>';
						echo '</td>';
						echo '<td>';
							echo '<input type="date" name="prioritySeatingDate" value="' . date('Y-m-d', $event->getPrioritySeatingTime()) . '" required>';
							echo '<input type="time" name="prioritySeatingTime" value="' . date('H:i', $event->getPrioritySeatingTime()) . '" required>';
						echo '</td>';
						echo '<td>';
							echo '<input type="date" name="seatingDate" value="' . date('Y-m-d', $event->getSeatingTime()) . '" required>';
							echo '<input type="time" name="seatingTime" value="' . date('H:i', $event->getSeatingTime()) . '" required>';
						echo '</td>';
						echo '<td>';
							echo '<input type="date" name="startDate" value="' . date('Y-m-d', $event->getStartTime()) . '" required>';
							echo '<input type="time" name="startTime" value="' . date('H:i', $event->getStartTime()) . '" required>';
						echo '</td>';
						echo '<td>';
							echo '<input type="date" name="endDate" value="' . date('Y-m-d', $event->getEndTime()) . '" required>';
							echo '<input type="time" name="endTime" value="' . date('H:i', $event->getEndTime()) . '" required>';
						echo '</td>';
						echo '<td>';
							echo '<input type="submit" value="Endre">';
							echo '<input type="button" value="Vis setekart" onClick="viewSeatmap(' . $event->getSeatmap()->getId() . ')">';
						echo '</td>';
					echo '</form>';

					echo '<td></td>';

					if ($user->hasPermission('*')) {
						$currentEvent = EventHandler::getCurrentEvent();

						// Allow user to transfer members from previus event if this event is the current one.
						if ($event->equals($currentEvent)) {
							echo '<td><input type="button" value="Kopier medlemmer" onClick="copyMembers(' . EventHandler::getPreviousEvent()->getId() . ')"></td>';
						}

						// Prevent users from removing events that have already started, we don't want to delete old tickets etc.
						if ($event->getBookingTime() >= $currentEvent->getBookingTime()) {
							echo '<td><input type="button" value="Slett" onClick="removeEvent(' . $event->getId() . ')"></td>';
						}
					}
				echo '</tr>';
			}
		echo '</table>';

		echo '<h3>Legg til nytt arrangement:</h3>';
		echo '<p>Fyll ut feltene under for å legge til en ny side.</p>';
		echo '<form class="admin-events-add" method="post">';
			echo '<table>';
				echo '<tr>';
					echo '<td>Sted:</td>';
					echo '<td>';
						echo '<select class="chosen-select" name="location">';
							foreach (LocationHandler::getLocations() as $location) {
								echo '<option value="' . $location->getId() . '">' . $location->getTitle() . '</option>';
							}
						echo '</select>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Deltakere:</td>';
					echo '<td><input type="number" name="participants" required></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Booking:</td>';
					echo '<td><input type="date" name="bookingDate" placeholder="' . date('Y-m-d') . '" required></td>';
					echo '<td><input type="time" name="bookingTime" placeholder="' . date('H:i:s') . '" required></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Start:</td>';
					echo '<td><input type="date" name="startDate" placeholder="' . date('Y-m-d') . '" required></td>';
					echo '<td><input type="time" name="startTime" placeholder="' . date('H:i:s') . '" required></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Slutt:</td>';
					echo '<td><input type="date" name="endDate" placeholder="' . date('Y-m-d') . '" required></td>';
					echo '<td><input type="time" name="endTime" placeholder="' . date('H:i:s') . '" required></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><input type="submit" value="Legg til"></td>';
				echo '</tr>';
			echo '</table>';
		echo '</form>';
	} else {
		echo 'Du har ikke rettigheter til dette!';
	}
} else {
	echo 'Du er ikke logget inn!';
}
?>
