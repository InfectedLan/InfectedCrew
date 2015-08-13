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
require_once 'handlers/seatmaphandler.php';
require_once 'handlers/eventhandler.php';
require_once 'handlers/tickethandler.php';
require_once 'handlers/grouphandler.php';

$id = isset($_GET['id']) ? $_GET['id'] : Session::getCurrentUser()->getId();

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	$profileUser = UserHandler::getUser($id);

	if ($profileUser != null) {
		if ($user->hasPermission('*') ||
			$user->hasPermission('search.users') ||
			$user->equals($profileUser)) {
			echo '<link rel="stylesheet" href="../api/styles/seatmap.css">';
			echo '<script src="scripts/my-profile.js"></script>';

			echo '<h3>' . $profileUser->getDisplayName(). '</h3>';
			echo '<table style="float: left;">';
				if ($user->hasPermission('*')) {
					echo '<tr>';
						echo '<td>Id:</td>';
						echo '<td>' . $profileUser->getId() . '</td>';
					echo '</tr>';
				}

				echo '<tr>';
					echo '<td>Navn:</td>';
					echo '<td>' . $profileUser->getFullName() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Brukernavn:</td>';
					echo '<td>' . $profileUser->getUsername() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>E-post:</td>';
					echo '<td><a href="mailto:' . $profileUser->getEmail() . '">' . $profileUser->getEmail() . '</a></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Fødselsdato</td>';
					echo '<td>' . date('d.m.Y', $profileUser->getBirthdate()) . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Kjønn:</td>';
					echo '<td>' . $profileUser->getGenderAsString() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Alder:</td>';
					echo '<td>' . $profileUser->getAge() . ' år</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Telefon:</td>';
					echo '<td>' . $profileUser->getPhoneAsString() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Adresse:</td>';
						$address = $profileUser->getAddress();

						if (!empty($address)) {
							echo '<td>' . $address . '</td>';
						} else {
							echo '<td><i>Ikke oppgitt</i></td>';
						}
				echo '</tr>';

				$postalCode = $profileUser->getPostalCode();

				if ($postalCode != 0) {
					echo '<tr>';
						echo '<td></td>';
						echo '<td>' . $postalCode . ' ' . $profileUser->getCity() . '</td>';
					echo '</tr>';
				}

				echo '<tr>';
					echo '<td>Kallenavn:</td>';
					echo '<td>' . $profileUser->getNickname() . '</td>';
				echo '</tr>';

				if ($profileUser->hasEmergencyContact()) {
					echo '<tr>';
						echo '<td>Foresatte\'s telefon:</td>';
						echo '<td>' . $profileUser->getEmergencyContact()->getPhoneAsString() . '</td>';
					echo '</tr>';
				}

				if ($user->hasPermission('*') ||
					$user->equals($profileUser)) {
					echo '<tr>';
						echo '<td>Dato registrert:</td>';
						echo '<td>' . date('d.m.Y', $profileUser->getRegisteredDate()) . '</td>';
					echo '</tr>';
				}

				if ($user->hasPermission('*')) {
					echo '<tr>';
						echo '<td>Aktivert:</td>';
						echo '<td>' . ($profileUser->isActivated() ? 'Ja' : 'Nei') . '</td>';
					echo '</tr>';
				}

				if ($profileUser->hasTicketsByAllEvents()) {
					echo '<tr>';
						echo '<td>Deltatt tidligere:</td>';
						echo '<td>' . count($profileUser->getTicketsByAllEvents()) . ' ganger</td>';
					echo '</tr>';
				}

				if ($profileUser->isGroupMember()) {
					echo '<tr>';
						echo '<td>Crew:</td>';
						echo '<td>';
							if ($profileUser->isGroupMember()) {
								echo $profileUser->getGroup()->getTitle();
							} else {
								echo '<i>Ingen</i>';
							}
						echo '</td>';
					echo '</tr>';

					if ($profileUser->isTeamMember()) {
						echo '<tr>';
							echo '<td>Lag:</td>';
							echo '<td>' . $profileUser->getTeam()->getTitle() . '</td>';
						echo '</tr>';
					}
				}

				if ($profileUser->hasTicket()) {
					$ticketList = $profileUser->getTickets();
					$ticketCount = count($ticketList);
					sort($ticketList);

					echo '<tr>';
						echo '<td>' . (count($ticketList) > 1 ? 'Billetter' : 'Billett') . ':</td>';
						echo '<td>';
							foreach ($ticketList as $ticket) {
								echo '<a href="index.php?page=ticket&id=' . $ticket->getId() . '">#' . $ticket->getId() . '</a>';

								// Only print comma if this is not the last ticket in the array.
								echo $ticket !== end($ticketList) ? ', ' : ' (' . $ticketCount . ')';
							}
						echo '</td>';
					echo '</tr>';
				}

				if ($profileUser->hasTicket() &&
					$profileUser->hasSeat()) {
					$ticket = $profileUser->getTicket();

					echo '<tr>';
						echo '<td>Plass:</td>';
						echo '<td>' . $ticket->getSeat()->getString() . '</td>';
					echo '</tr>';
				}

				if ($user->hasPermission('*') ||
					$user->equals($profileUser)) {
					echo '<tr>';
						echo '<td></td>';
						echo '<td><a href="index.php?page=edit-profile&id=' . $profileUser->getId() . '">Endre bruker</a></td>';
					echo '</tr>';
				}

				if ($user->equals($profileUser)) {
					echo '<tr>';
						echo '<td></td>';
						echo '<td><a href="index.php?page=edit-avatar">Endre avatar</a></td>';
					echo '</tr>';
				}

				if ($user->hasPermission('*') ||
					$user->hasPermission('admin.permissions')) {
					echo '<tr>';
						echo '<td></td>';
						echo '<td><a href="index.php?page=admin-permissions&id=' . $profileUser->getId() . '">Endre rettigheter</a></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td></td>';
						echo '<td><a href="index.php?page=user-history">Vis historie</a></td>';
					echo '</tr>';
				}

				if ($user->hasPermission('*') ||
					$user->hasPermission('admin.permissions')) {

					if (!$profileUser->isGroupMember()) {
						echo '<tr>';
							echo '<td></td>';
							echo '<td>';
								echo '<form class="my-profile-group-add-user" method="post">';
									echo '<input type="hidden" name="userId" value="' . $profileUser->getId() . '">';
									echo '<select class="chosen-select" name="groupId">';
										foreach (GroupHandler::getGroups() as $group) {
											echo '<option value="' . $group->getId() . '">' . $group->getTitle() . '</option>';
										}
									echo '</select> ';
									echo '<input type="submit" value="Legg til i crew">';
								echo '</form>';
							echo '</td>';
						echo '</tr>';
					}
				}
			echo '</table>';

			$avatarFile = null;

			if ($profileUser->hasValidAvatar()) {
				$avatarFile = $profileUser->getAvatar()->getHd();
			} else {
				$avatarFile = AvatarHandler::getDefaultAvatar($profileUser);
			}

			echo '<img src="../api/' . $avatarFile . '" width="50%" style="float: right;">';

			if (($user->hasPermission('*') ||
				$user->hasPermission('search.users') ||
				$user->hasPermission('chief.tickets')) && // TODO: Verify this permission.
				$profileUser->hasTicket()) {
				$ticket = $profileUser->getTicket();
				echo '<script src="../api/scripts/seatmapRenderer.js"></script>';

				echo '<h3>Omplasser bruker</h3>';
				echo '<div id="seatmapCanvas"></div>';
				echo '<script>';
					echo 'var seatmapId = ' . $ticket->getEvent()->getSeatmap()->getId() . ';';
					echo 'var ticketId = ' . $ticket->getId() . ';';
					echo '$(document).ready(function() {';
						echo 'downloadAndRenderSeatmap("#seatmapCanvas", seatHandlerFunction, callback);';
					echo '});';
				echo '</script>';
			}
		} else {
			echo '<p>Du har ikke rettigehter til dette.</p>';
		}
	} else {
		echo '<p>Brukeren du ser etter finnes ikke.</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>
