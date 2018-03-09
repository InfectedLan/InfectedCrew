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
require_once 'handlers/notehandler.php';
require_once 'utils/dateutils.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('event.checklist')) {
		echo '<script src="scripts/event-checklist.js"></script>';
		echo '<style>';
			echo 'table {';
				echo 'border-spacing: 0px;';
			echo '}';

			echo 'th {';
				echo 'text-align: left;';
			echo '}';

			echo 'td {';
				echo 'padding: 4px;';
			echo '}';
		echo '</style>';

		echo '<h3>Sjekklister</h3>';
		echo '<p>Dette er sjekklistene dine, gå igjennom å huk av når ting er gjort, eller klikk nederet på siden for å endre dem.</p>';
		echo '<img src="images/checklist-description.jpg" alt="Beskrivelse av farger">';

		if ($user->isGroupMember()) {
			$group = $user->getGroup();
			$commonNoteList = NoteHandler::getNotesByGroupAndTeamAndUser($user);

			if (!empty($commonNoteList)) {
				echo '<h3>Sjekkliste for din stilling</h3>';
				echo getNotelist($commonNoteList, false);
			}
		}

		$privateNoteList = NoteHandler::getNotesByUser($user);

		if (!empty($privateNoteList)) {
			echo '<h3>Din private sjekkliste</h3>';
			echo getNotelist($privateNoteList, false);
		}

		if (empty($commonNoteList) && empty($privateNoteList)) {
			echo '<p>Det er ikke opprettet noe gjøremål i sjekklisten enda, du kan legge til gjøremål under.</p>';
		}

		echo '<h3>Legg til ett nytt gjøremål:</h3>';
		echo '<p>Fyll ut feltene under for å legge til et nytt gjøremål.</p>';

		echo addNote();

		if ($user->hasPermission('event.checklist.list')) {
			$noteList = NoteHandler::getNotes();

			if (!empty($noteList)) {
				echo '<h3>Oversikt over alle gjøremål for hele crewet</h3>';
				echo getNotelist($noteList, true);
			}
		}
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}

function getNotelist(array $noteList, $showAdditionalInfo) {
	$content = null;

	if (Session::isAuthenticated()) {
		$user = Session::getCurrentUser();

		if ($user->isGroupMember()) {
			$group = $user->getGroup();

			$content .= '<table>';
				$content .= '<tr>';
					$content .= '<th>Ferdig?</th>';
					$content .= '<th>Crew</th>';
					$content .= '<th>Lag</th>';
					$content .= '<th>Oppgave</th>';
					$content .= '<th>Tidspunkt</th>';
					$content .= '<th>Ansvarlig</th>';
					$content .= '<th>Detaljer</th>';
					$content .= '<th>Tilskuere</th>';
					$content .= '<th>Påbegynt</th>';
				$content .= '</tr>';

				foreach ($noteList as $note) {
					$color = "#ffffff";

					if ($note->isDone()) { // Punker som er ferdig: Teskten blir grønn
						$color = "#44ce44"; // Green
					} else if ($note->isInProgress()) { // Punker som er påbegynt: Teskten blir orange
						$color = "#ff6600"; // Orange
					} else if ($note->isExpired()) { // Punkter som er over tiden: Tesksten blir rød
						$color = "#ff5151"; // Red
					} else if ($note->isDelegated() && $note->isUser($user)) { // Punkter du har fått delegert: En annen blåtone bakgrunn
						$color = "#8acfff"; // Blue tone
					} else if ($note->isDelegated() && $note->isOwner($user)) { // Punkter du har delegert bort: lyslilla bakgrunn
						$color = "#b289e1"; // Purple
					} else if (!$note->isPrivate() && $note->isUser($user)) { // Stilling: Blå bakgrunn
						$color = "#3f94ff"; // Blue
					}

					$content .= '<tr style="background: ' . $color . ';">';
							$content .= '<td style="padding-left: 16px;">';
								$content .= '<form class="event-checklist-check" method="post">';
									$content .= '<input type="hidden" name="id" value="' . $note->getId() . '" />';
									$content .= '<input type="checkbox" name="done" value="1"' . ($note->isDone() ? ' checked' : null) . '>';
								$content .= '</form>';
							$content .= '</td>';

							if ($note->hasGroup()) {
								$content .= '<td>' . $note->getGroup()->getTitle() . '</td>';
							} else {
								$content .= '<td>Ingen</td>';
							}

							if ($note->hasTeam()) {
								$content .= '<td>' . $note->getTeam()->getTitle() . '</td>';
							} else {
								$content .= '<td>Ingen</td>';
							}

							$content .= '<td>' . $note->getTitle() . '</td>';
							$content .= '<td>';
								$secondsOffset = $note->getSecondsOffset();

								if ($secondsOffset >= -86400 && $secondsOffset <= 172800) {
									$content .= DateUtils::getDayFromInt(date('w', $note->getAbsoluteTime())) . ' ' . date('H:i', $note->getAbsoluteTime());
								} else {
									$week = abs(round($secondsOffset / 604800));
									$content .= $week . ' ' . ($week > 1 ? 'uker' : 'uke') . ' før';
								}
							$content .= '</td>';
							$content .= '<td>' . ($note->hasOwner() || $note->hasUser($user) ? $note->getUser()->getFirstname() : 'Ingen') . '</td>';
							$content .= '<td>';
								$content .= '<div class="slidingBox">';
									$content .= '<a href="#" class="show_hide">Vis</a>';
									$content .= '<div class="details">' . $note->getContent() . '</div>';
								$content .= '</div>';
							$content .= '</td>';
							$content .= '<td>';
								$watchingUserList = $note->getWatchingUsers();

								if (count($watchingUserList) > 0) {
									$content .= '<div class="slidingBox">';
										$content .= '<a href="#" class="show_hide">Vis</a>';
										$content .= '<div class="details">';

											foreach ($watchingUserList as $watchingUser) {
												$content .= $watchingUser->getFirstname();

												$content .= (!end($watchingUserList)->equals($watchingUser) ? ', ' : '');
											}

										$content .= '</div>';
									$content .= '</div>';
								}

							$content .= '</td>';
							$content .= '<td>';
							$content .= '<form class="event-checklist-check" method="post">';
								$content .= '<input type="hidden" name="id" value="' . $note->getId() . '" />';
								$content .= '<input type="checkbox" name="inProgress" value="1" onClick="testNote(' . $note->getId() . ')"' . ($note->isInProgress() ? ' checked' : null) . '>';
							$content .= '</form>';
						$content .= '<td><input type="button" value="Endre" onClick="editNote(' . $note->getId() . ')"></td>';

						if ($user->hasPermission('*') ||
							$note->isOwner($user)) {
							$content .= '<td><input type="button" value="Fjern" onClick="removeNote(' . $note->getId() . ')"></td>';
						}

					$content .= '</tr>';
				}

			$content .= '</table>';
		}
	}

	return $content;
}

function addNote() {
	$content = null;

	if (Session::isAuthenticated()) {
		$user = Session::getCurrentUser();

		if ($user->isGroupMember()) {
			$group = $user->getGroup();

			$content .= '<form class="event-checklist-add" method="post">';
				$content .= '<table>';

					if ($user->hasPermission('*') ||
						$user->isGroupLeader() ||
						($user->isTeamMember() && $user->isTeamLeader())) {
						$content .= '<tr>';
							$content .= '<td>Er dette privat?</td>';
							$content .= '<td>';
								$content .= '<select class="chosen-select event-checklist-add-private" style="width:20%;" name="private">';
									$content .= '<option value="0">Stillingsbasert</option>';
									$content .= '<option value="1">Privat</option>';
								$content .= '</select> <i>Privat er for deg, Stillingsbasert låses til din rolle, og kan tildeles andre.</i>';
							$content .= '</td>';
						$content .= '</tr>';
					}

					$content .= '<tr>';
						$content .= '<td>Oppgave</td>';
						$content .= '<td><input type="text" name="title" placeholder="Skriv inn et gjøremål her..." required></td>';
					$content .= '</tr>';
					$content .= '<tr>';
						$content .= '<td>Detaljer</td>';
						$content .= '<td>';
							$content .= '<textarea name="content" rows="2" cols="80" placeholder="Skriv detaljer rundt gjøremålet her..." required></textarea>';
						$content .= '</td>';
					$content .= '</tr>';
					$content .= '<tr>';
						$content .= '<td>Dag/Tidspunkt</td>';
						$content .= '<td>';
							$content .= '<select class="chosen-select event-checklist-add-secondsOffset" style="width:20%;" name="secondsOffset">';
								$content .= '<option value="172800">Søndag</option>'; // Søndag.
								$content .= '<option value="86400">Lørdag</option>'; // Lørdag.
								$content .= '<option value="0">Fredag</option>'; // Fredag.
								$content .= '<option value="-86400">Torsdag</option>'; // Torsdag.

								// Adding weeks.
								for ($week = 1; $week <= 8; $week++) {
									$seconds = -$week * 604800;

									$content .= '<option value="' . $seconds . '">' . $week . ' ' . ($week > 1 ? 'uker' : 'uke') . ' før</option>';
								}

							$content .= '</select>';
							$content .= '<input type="time" name="time" class="event-checklist-add-time" placeholder="00:00" value="' . date('H:i') . '">';
						$content .= '</td>';
					$content .= '</tr>';
					$content .= '<div class="edit-checklist-add-nonPrivate">';

						if ($user->isGroupLeader()) {
							$content .= '<tr>';
								$content .= '<td>Deleger til lag-leder</td>';
								$content .= '<td>';
									$content .= '<select class="chosen-select event-checklist-add-teamId" style="width:20%;" name="teamId">';
										$content .= '<option value="0">Ingen</option>';

										foreach ($group->getTeams() as $team) {
											$content .= '<option value="' . $team->getId() . '">' . $team->getTitle() . '</option>';
										}

									$content .= '</select>';
								$content .= '</td>';
							$content .= '</tr>';
						}

						if ($user->hasPermission('event.checklist.delegate') || $user->isGroupLeader() ||
							($user->isTeamMember() && $user->isTeamLeader())) {
							$content .= '<tr>';
								$content .= '<td>Deleger til medlem</td>';
								$content .= '<td>';
									$content .= '<select class="chosen-select event-checklist-add-userId" name="userId">';
										$content .= '<option value="0">Ingen</option>';

										$memberList = $user->hasPermission('event.checklist.delegate') ? UserHandler::getMemberUsers() : $group->getMembers();

										foreach ($memberList as $member) {
											if (!$member->equals($user)) {
												$content .= '<option value="' . $member->getId() . '">' . $member->getDisplayName() . '</option>';
											}
										}

									$content .= '</select>';
								$content .= '</td>';
							$content .= '</tr>';
						}

					$content .= '</div>';
				$content .= '</table>';
				$content .= '<input type="submit" value="Legg til">';
			$content .= '</form>';
		}
	}

	return $content;
}
?>
