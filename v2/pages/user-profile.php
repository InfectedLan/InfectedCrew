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
	$editUser = UserHandler::getUser($id);

	if ($editUser != null) {
		if ($user->hasPermission('user.search') ||
			$user->equals($editUser)) {
			echo '<script src="scripts/user-profile.js"></script>';

			echo '<h3>' . $editUser->getDisplayName(). '</h3>';
			echo '<table style="float: left;">';
				if ($user->hasPermission('*')) {
					echo '<tr>';
						echo '<td>Id:</td>';
						echo '<td>' . $editUser->getId() . '</td>';
					echo '</tr>';
				}

				echo '<tr>';
					echo '<td>Navn:</td>';
					echo '<td>' . $editUser->getFullName() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Brukernavn:</td>';
					echo '<td>' . $editUser->getUsername() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>E-post:</td>';
					echo '<td><a href="mailto:' . $editUser->getEmail() . '">' . $editUser->getEmail() . '</a></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Fødselsdato</td>';
					echo '<td>' . date('d.m.Y', $editUser->getBirthdate()) . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Kjønn:</td>';
					echo '<td>' . $editUser->getGenderAsString() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Alder:</td>';
					echo '<td>' . $editUser->getAge() . ' år</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Telefon:</td>';
					echo '<td>' . $editUser->getPhoneAsString() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Adresse:</td>';
						$address = $editUser->getAddress();

						if (!empty($address)) {
							echo '<td>' . $address . '</td>';
						} else {
							echo '<td><i>Ikke oppgitt</i></td>';
						}
				echo '</tr>';

				$postalCode = $editUser->getPostalCode();

				if ($postalCode != 0) {
					echo '<tr>';
						echo '<td></td>';
						echo '<td>' . $postalCode . ' ' . $editUser->getCity() . '</td>';
					echo '</tr>';
				}

				echo '<tr>';
					echo '<td>Kallenavn:</td>';
					echo '<td>' . $editUser->getNickname() . '</td>';
				echo '</tr>';

				if ($editUser->hasEmergencyContact()) {
					echo '<tr>';
						echo '<td>Foresatte\'s telefon:</td>';
						echo '<td>' . $editUser->getEmergencyContact()->getPhoneAsString() . '</td>';
					echo '</tr>';
				}

				if ($user->hasPermission('*') ||
					$user->equals($editUser)) {
					echo '<tr>';
						echo '<td>Dato registrert:</td>';
						echo '<td>' . date('d.m.Y', $editUser->getRegisteredDate()) . '</td>';
					echo '</tr>';
				}

				if ($user->hasPermission('user.activate')) {
					echo '<tr>';
						echo '<td>Aktivert:</td>';
						echo '<td>';
							echo ($editUser->isActivated() ? 'Ja' : 'Nei');

							if (!$editUser->isActivated()) {
								echo '<input type="button" value="Aktiver" onClick="activateUser(' . $editUser->getId() . ')">';
							}
						echo '</td>';
					echo '</tr>';
				}

				$historyEventCount = count($editUser->getParticipatedEvents());

				echo '<tr>';
					echo '<td>Deltatt tidligere:</td>';
					echo '<td>' . ($historyEventCount <= 0 ? 'Nei' : $historyEventCount . ' ' . ($historyEventCount > 1 ? 'ganger' : 'gang')) . '</td>';
				echo '</tr>';

				if ($editUser->isGroupMember()) {
					$group = $editUser->getGroup();

					echo '<tr>';
						echo '<td>' . ($editUser->isTeamMember() ? 'Crew/Lag:' : 'Crew') . '</td>';
						echo '<td>' . ($editUser->isTeamMember() ? $group->getTitle() . ':' . $editUser->getTeam()->getTitle() : $group->getTitle()) . '</td>';
					echo '</tr>';
				}

				if ($editUser->hasTicket()) {
					$ticketList = $editUser->getTickets();
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

				if ($editUser->hasTicket() &&
					$editUser->hasSeat()) {
					$ticket = $editUser->getTicket();

					echo '<tr>';
						echo '<td>Plass:</td>';
						echo '<td>' . $ticket->getSeat()->getString() . '</td>';
					echo '</tr>';
				}
				if($user->hasPermission('compo.management')) {
				    $steamId = $editUser->getSteamId();
				    if($steamId !== null) {
					echo '<tr>';
						echo '<td>Steam id</td>';
						echo '<td><a href="https://steamcommunity.com/profiles/' . $steamId . '">' . $steamId . '</a></td>';
					echo '</tr>';
				    } else {
					echo '<tr>';
						echo '<td>Steam id</td>';
						echo '<td><i>Ingen</i></td>';
					echo '</tr>';
				    }
				}

				if ($user->hasPermission('user.profile')) {
					echo '<tr>';
						echo '<td>Svømming:</td>';
						echo '<td>';
							echo $editUser->isSwimming() ? 'Ja' : 'Nei';
							echo '<input type="button" value="Endre" onClick="setUserSwimming(' . $editUser->getId() . ', ' . ($editUser->isSwimming() ? '0' : '1') . ')">';
						echo '</td>';
					echo '</tr>';
				}

				if ($user->hasPermission('user.history') ||
					$user->equals($editUser)) {
					echo '<tr>';
						echo '<td></td>';
						echo '<td><a href="index.php?page=user-history&id=' . $editUser->getId() . '">Vis historikk</a></td>';
					echo '</tr>';
				}

				if ($user->hasPermission('user.edit') ||
					$user->equals($editUser)) {
					echo '<tr>';
						echo '<td></td>';
						echo '<td><a href="index.php?page=edit-profile&id=' . $editUser->getId() . '">Endre bruker</a></td>';
					echo '</tr>';
				}

				if ($user->hasPermission('user.relocate') ||
					$user->equals($editUser)) {
					if ($editUser->hasTicket()) {
						echo '<tr>';
							echo '<td></td>';
							echo '<td><a href="index.php?page=edit-user-location&id=' . $editUser->getId() . '">Endre plassering</a></td>';
						echo '</tr>';
					}
				}

				if ($user->equals($editUser)) {
					echo '<tr>';
						echo '<td></td>';
						echo '<td><a href="index.php?page=edit-avatar">Endre avatar</a></td>';
					echo '</tr>';
				}

				if ($user->hasPermission('admin.permissions')) {
					echo '<tr>';
						echo '<td></td>';
						echo '<td><a href="index.php?page=admin-permissions&id=' . $editUser->getId() . '">Endre rettigheter</a></td>';
					echo '</tr>';
				}

				if ($user->hasPermission('*')) {
					if (!$editUser->isGroupMember()) {
						echo '<tr>';
							echo '<td></td>';
							echo '<td>';
								echo '<form class="user-profile-group-add-user" method="post">';
									echo '<input type="hidden" name="userId" value="' . $editUser->getId() . '">';
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

				echo '<script src="scripts/edit-user-note.js"></script>';

				if ($user->hasPermission('user.note')) {

						echo '<form class="edit-user-note" method="post">';
							echo '<input type="hidden" name="id" value="' . $editUser->getId() . '">';
							echo '<tr>';
								echo '<td>Notat:</td>';
								echo '<td><textarea name="content" rows="5" cols="30" placeholder="Skriv inn et notat her...">' . ($editUser->hasNote() ? $editUser->getNote() : null) . '</textarea></td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td></td>';
								echo '<td><input type="submit" value="' . ($editUser->hasNote() ? 'Lagre notat' : 'Legg til notat') . '"></td>';
							echo '</tr>';
						echo '</form>';

				}

			echo '</table>';

			$avatarFile = null;

			if ($editUser->hasValidAvatar()) {
				$avatarFile = $editUser->getAvatar()->getHd();
			} else {
				$avatarFile = AvatarHandler::getDefaultAvatar($editUser);
			}

			echo '<img src="../api/' . $avatarFile . '" width="50%" style="float: right;">';
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
