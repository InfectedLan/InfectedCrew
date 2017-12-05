<?php
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

require_once 'session.php';
require_once 'handlers/eventhandler.php';
require_once 'handlers/locationhandler.php';
require_once 'admin.php';

class AdminEventPage extends AdminPage {
	public function hasParent(): bool {
		return true;
	}

	public function getTitle(): string {
		return 'Arrangementer';
	}

	public function getContent(): string {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if ($user->hasPermission('admin.event')) {
				$content .= '<div class="row">';
					$content .= '<div class="col-md-6">';
						$eventList = EventHandler::getEvents(); // TODO: Change this function to sort event by newest first in SQL.

						// Sort this array so that we show newest events first.
						rsort($eventList);

						if (!empty($eventList)) {
							foreach ($eventList as $event) {
						  	$content .= '<div class="box">';
									$content .= '<div class="box-header">';
								  	$content .= '<h3 class="box-title">' . $event->getTitle() . '</h3>';
									$content .= '</div>';
									$content .= '<div class="box-body">';
										$content .= $this->getEditForm($event, $user);
									$content .= '</div>';
								$content .= '</div>';
							}
						} else {
							$content .= '<div class="box">';
								$content .= '<div class="box-body">';
									$content .= '<p>Det har ikke blitt opprettet noen arrangementer enda.</p>';
								$content .= '</div>';
							$content .= '</div>';
						}

					$content .= '</div>';
					$content .= '<div class="col-md-6">';
					  $content .= '<div class="box">';
							$content .= '<div class="box-header">';
						  	$content .= '<h3 class="box-title">Legg til et nytt arrangement</h3>';
							$content .= '</div>';
							$content .= '<div class="box-body">';
								$content .= '<p>Fyll ut feltene under for å legge til et nytt arrangement.</p>';
								$content .= $this->getAddForm(EventHandler::getCurrentEvent());
							$content .= '</div>';
					  $content .= '</div>';
					$content .= '</div>';
				$content .= '</div>';
			} else {
				$content .= '<div class="box">';
					$content .= '<div class="box-body">';
						$content .= '<p>Du har ikke rettigheter til dette!</p>';
					$content .= '</div>';
				$content .= '</div>';
			}
		} else {
			$content .= '<div class="box">';
				$content .= '<div class="box-body">';
					$content .= '<p>Du er ikke logget inn!</p>';
				$content .= '</div>';
			$content .= '</div>';
		}

		$content .= '<script src="scripts/admin-event.js"></script>';

		return $content;
	}

	private function getAddForm(Event $event): string {
		$content = null;

		$content .= '<form class="admin-event-add" method="post">';
			$content .= '<div class="form-group">';
				$content .= '<label>Sted</label>';
				$content .= '<select class="form-control" name="location" required>';

					foreach (LocationHandler::getLocations() as $location) {
						$content .= '<option value="' . $location->getId() . '">' . $location->getTitle() . '</option>';
					}

				$content .= '</select>';
			$content .= '</div>';
			$content .= '<div class="form-group">';
				$content .= '<label>Anstall deltakere</label>';
				$content .= '<input type="number" class="form-control" name="participants" value="' . $event->getParticipants() . '" required>';
			$content .= '</div>';
			$content .= '<div class="form-group">';
				$content .= '<label>Billetsalgs dato og tid</label>';
				$content .= '<div class="input-group">';
					$content .= '<div class="input-group-addon">';
					$content .= '<i class="fa fa-clock-o"></i>';
					$content .= '</div>';
					$content .= '<input type="text" class="form-control pull-right" name="bookingTime" id="datetime" value="' . date('Y-m-d H:i:s', $event->getBookingTime()) . '" required>';
				$content .= '</div>';
			$content .= '</div>';
			$content .= '<div class="form-group">';
				$content .= '<label>Startdato og tid</label>';
				$content .= '<div class="input-group">';
					$content .= '<div class="input-group-addon">';
						$content .= '<i class="fa fa-clock-o"></i>';
					$content .= '</div>';
					$content .= '<input type="text" class="form-control pull-right" name="startTime" id="datetime" value="' . date('Y-m-d H:i:s', $event->getStartTime()) . '" required>';
				$content .= '</div>';
			$content .= '</div>';
			$content .= '<div class="form-group">';
				$content .= '<label>Startdato og tid</label>';
				$content .= '<div class="input-group">';
					$content .= '<div class="input-group-addon">';
						$content .= '<i class="fa fa-clock-o"></i>';
					$content .= '</div>';
					$content .= '<input type="text" class="form-control pull-right" name="endTime" id="datetime" value="' . date('Y-m-d H:i:s', $event->getEndTime()) . '" required>';
				$content .= '</div>';
			$content .= '</div>';
			$content .= '<button type="submit" class="btn btn-primary">Legg til</button>';
		$content .= '</form>';

		return $content;
	}

	private function getEditForm(Event $event, User $user): string {
		$content = null;

		$content .= '<form class="admin-event-edit" method="post">';
			$content .= '<input type="hidden" name="id" value="' . $event->getId() . '">';
			$content .= '<div class="form-group">';
				$content .= '<label>Sted</label>';
				$content .= '<select class="form-control" name="location" required>';

					foreach (LocationHandler::getLocations() as $location) {
						if ($location->equals($event->getLocation())) {
							$content .= '<option value="' . $location->getId() . '" selected>' . $location->getTitle() . '</option>';
						} else {
							$content .= '<option value="' . $location->getId() . '">' . $location->getTitle() . '</option>';
						}
					}

				$content .= '</select>';
			$content .= '</div>';
			$content .= '<div class="form-group">';
				$content .= '<label>Anstall deltakere</label>';
				$content .= '<input type="number" class="form-control" name="participants" value="' . $event->getParticipants() . '" required>';
			$content .= '</div>';
			$content .= '<div class="form-group">';
				$content .= '<label>Billetsalgsdato og tid</label>';
				$content .= '<div class="input-group">';
					$content .= '<div class="input-group-addon">';
					$content .= '<i class="fa fa-clock-o"></i>';
					$content .= '</div>';
					$content .= '<input type="text" class="form-control pull-right" name="bookingTime" value="' . date('Y-m-d H:i:s', $event->getBookingTime()) . '" required>';
				$content .= '</div>';
				$content .= '</div>';
			$content .= '<div class="form-group">';
				$content .= '<label>Startdato og tid</label>';
				$content .= '<div class="input-group">';
					$content .= '<div class="input-group-addon">';
						$content .= '<i class="fa fa-clock-o"></i>';
					$content .= '</div>';
					$content .= '<input type="text" class="form-control pull-right" name="startTime" value="' . date('Y-m-d H:i:s', $event->getStartTime()) . '" required>';
				$content .= '</div>';
			$content .= '</div>';
			$content .= '<div class="form-group">';
				$content .= '<label>Startdato og tid</label>';
				$content .= '<div class="input-group">';
					$content .= '<div class="input-group-addon">';
						$content .= '<i class="fa fa-clock-o"></i>';
					$content .= '</div>';
					$content .= '<input type="text" class="form-control pull-right" name="endTime" value="' . date('Y-m-d H:i:s', $event->getEndTime()) . '" required>';
				$content .= '</div>';
			$content .= '</div>';
			$content .= '<div class="btn-group" role="group" aria-label="...">';
				$content .= '<button type="submit" class="btn btn-primary">Endre</button>';

				if ($user->hasPermission('*')) {
					$currentEvent = EventHandler::getCurrentEvent();

					// Prevent users from removing events that have already started, we don't want to delete old tickets etc.
					if ($event->getBookingTime() >= $currentEvent->getBookingTime()) {
						$content .= '<button type="button" class="btn btn-primary" onClick="removeEvent(' . $event->getId() . ')">Fjern</button>';
					}

					// Allow user to transfer members from previus event if this event is the current one.
					if ($event->equals($currentEvent)) {
						$previousEvent = EventHandler::getPreviousEvent();

						$content .= '<button type="button" class="btn btn-primary" onClick="copyMembers(' . $previousEvent->getId() . ')">Kopier medlemmer fra "' . $previousEvent->getTitle() . '"</button>';
					}
				}

				$content .= '<button type="button" class="btn btn-primary" onClick="viewSeatmap(' . $event->getSeatmap()->getId() . ')">Vis setekart</button>';
			$content .= '</div>';
		$content .= '</form>';

		return $content;
	}
}
?>
