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

require_once 'admin.php';
require_once 'session.php';
require_once 'handlers/eventhandler.php';
require_once 'handlers/locationhandler.php';
require_once 'interfaces/page.php';

class AdminEventsPage extends AdminPage implements IPage {
	public function getTitle() {
		return 'Arrangementer';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->hasPermission('*') ||
				$user->hasPermission('admin.events')) {
				$content .= '<script src="scripts/admin-events.js"></script>';
				
				$content .= '<p>Her er en liste over Infected arrangementer som har vært eller skal være. Neste arrangement blir automatisk vist på hovedsiden.</p>';
				
				$content .= '<table>';
					$content .= '<tr>';
						$content .= '<th>Navn:</th>';
						$content .= '<th>Sted/Deltakere:</th>';
						$content .= '<th>Booking:</th>';
						$content .= '<th>Start:</th>';
						$content .= '<th>Slutt:</th>';
					$content .= '</tr>';
					
					foreach (EventHandler::getEvents() as $event) {
						$content .= '<tr>';
							$content .= '<form class="admin-events-edit" name="input" method="post">';
								$content .= '<input type="hidden" name="id" value="' . $event->getId() . '">';
								$content .= '<td>' . $event->getTitle() . '</td>';
								$content .= '<td>';
									$content .= '<select class="chosen-select" name="location" required>';
										$content .= '<option value="' . $event->getLocation()->getId() . '">' . $event->getLocation()->getTitle() . '</option>';
									$content .= '</select>';
									$content .= '<input type="number" name="participants" value="' . $event->getParticipants() . '" required>';
								$content .= '</td>';
								$content .= '<td>';
									$content .= '<input type="date" name="bookingDate" value="' . date('Y-m-d', $event->getBookingTime()) . '" required>';
									$content .= '<input type="time" name="bookingTime" value="' . date('H:i', $event->getBookingTime()) . '" required>';
								$content .= '</td>';
								$content .= '<td>';
									$content .= '<input type="date" name="startDate" value="' . date('Y-m-d', $event->getStartTime()) . '" required>';
									$content .= '<input type="time" name="startTime" value="' . date('H:i', $event->getStartTime()) . '" required>';
								$content .= '</td>';
								$content .= '<td>';
									$content .= '<input type="date" name="endDate" value="' . date('Y-m-d', $event->getEndTime()) . '" required>';
									$content .= '<input type="time" name="endTime" value="' . date('H:i', $event->getEndTime()) . '" required>';
								$content .= '</td>';
								$content .= '<td>';
									$content .= '<input type="submit" value="Endre">';
									$content .= '<input type="button" value="Vis setekart" onClick="viewSeatmap(' . $event->getSeatmap()->getId() . ')">';
								$content .= '</td>';
							$content .= '</form>';
							
							$content .= '<td></td>';

							if ($user->hasPermission('*')) {
								$currentEvent = EventHandler::getCurrentEvent();

								// Allow user to transfer members from previus event if this event is the current one.
								if ($event->equals($currentEvent)) {
									$content .= '<td><input type="button" value="Kopier medlemmer" onClick="copyMembers(' . EventHandler::getPreviousEvent()->getId() . ')"></td>';
								}

								// Prevent users from removing events that have already started, we don't want to delete old tickets etc.
								if ($event->getBookingTime() >= $currentEvent->getBookingTime()) {
									$content .= '<td><input type="button" value="Slett" onClick="removeEvent(' . $event->getId() . ')"></td>';
								}
							}
						$content .= '</tr>';
					}
				$content .= '</table>';
				
				$content .= '<h3>Legg til nytt arrangement:</h3>';
				$content .= '<p>Fyll ut feltene under for å legge til en ny side.</p>';
				$content .= '<form class="admin-events-add" method="post">';
					$content .= '<table>';
						$content .= '<tr>';
							$content .= '<td>Sted:</td>';
							$content .= '<td>';
								$content .= '<select class="chosen-select" name="location">';
									foreach (LocationHandler::getLocations() as $location) {
										$content .= '<option value="' . $location->getId() . '">' . $location->getTitle() . '</option>';
									}
								$content .= '</select>';
							$content .= '</td>';
						$content .= '</tr>';
						$content .= '<tr>';
							$content .= '<td>Deltakere:</td>';
							$content .= '<td><input type="number" name="participants" required></td>';
						$content .= '</tr>';
						$content .= '<tr>';
							$content .= '<td>Booking:</td>';
							$content .= '<td><input type="date" name="bookingDate" placeholder="' . date('Y-m-d') . '" required></td>';
							$content .= '<td><input type="time" name="bookingTime" placeholder="' . date('H:i:s') . '" required></td>';
						$content .= '</tr>';
						$content .= '<tr>';
							$content .= '<td>Start:</td>';
							$content .= '<td><input type="date" name="startDate" placeholder="' . date('Y-m-d') . '" required></td>';
							$content .= '<td><input type="time" name="startTime" placeholder="' . date('H:i:s') . '" required></td>';
						$content .= '</tr>';
						$content .= '<tr>';
							$content .= '<td>Slutt:</td>';
							$content .= '<td><input type="date" name="endDate" placeholder="' . date('Y-m-d') . '" required></td>';
							$content .= '<td><input type="time" name="endTime" placeholder="' . date('H:i:s') . '" required></td>';
						$content .= '</tr>';
						$content .= '<tr>';
							$content .= '<td><input type="submit" value="Legg til"></td>';
						$content .= '</tr>';
					$content .= '</table>';
				$content .= '</form>';
			} else {
				$content .= 'Du har ikke rettigheter til dette!';
			}
		} else {
			$content .= 'Du er ikke logget inn!';
		}

		return $content;
	}
}
?>