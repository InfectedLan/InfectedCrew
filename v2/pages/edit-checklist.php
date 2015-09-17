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
require_once 'handlers/userhandler.php';
require_once 'handlers/grouphandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('event.checklist')) {
		echo '<script src="scripts/edit-checklist.js"></script>';
		echo '<h3>Endre sjekklister</h3>';

		echo '<p>Dette er sjekklister der du kan legge til punkter du/andre skal huske på.<br>';
		echo 'Du har en privat sjekkliste som bare er din, om du har en spesiell stilling, har du en for den og.<br>';
		echo 'Kan velge å få et e-postvarsel tidsfristen holder på å løpe ut.</p>';

		if ($user->isGroupMember()) {
			$group = $user->getGroup();
			$commonNoteList = NoteHandler::getNotesByGroupAndTeamAndUser($user);

			if (!empty($commonNoteList)) {
				echo '<h3>Sjekkliste for ' . $group->getTitle() . '</h3>';
				echo '<p>Er kan du legge til oppgaver for crew\'et ditt.<br>';
				echo 'Du kan sette oppgaven på et lag, da vil lag-leder få opp denne hos seg.<br>';
				echo 'Eller så kan du deligere en oppgave direkte til et medlem av crewet ditt.</p>';

				echo getNoteList($commonNoteList, false);
			}
		}

		$privateNoteList = NoteHandler::getNotesByUser($user);

		if (!empty($privateNoteList)) {
			echo '<h3>Din private sjekkliste</h3>';
			echo '<p>Her kan du legge til oppgaver som bare gjelder deg. <br>';
			echo 'Disse vil ikke være synlige for noen andre.</p>';

			echo getNoteList($privateNoteList, true);
		}

		if (empty($commonNoteList) && empty($privateNoteList)) {
			echo '<p>Det er ikke opprettet noe gjøremål i sjekklisten enda, du kan legge til gjøremål under.</p>';
		}

		echo '<h3>Legg til ett nytt gjøremål:</h3>';
		echo '<p>Fyll ut feltene under for å legge til et nytt gjøremål.</p>';

		echo addNote();
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}

function addNote() {
	$content = null;

	if (Session::isAuthenticated()) {
		$user = Session::getCurrentUser();

		if ($user->isGroupMember()) {
			$group = $user->getGroup();

			$content .= '<form class="edit-checklist-add" method="post">';
				$content .= '<table>';

					if ($user->hasPermission('*') ||
						$user->isGroupLeader() ||
						$user->isGroupCoLeader() ||
						($user->isTeamMember() && $user->isTeamLeader())) {
						$content .= '<tr>';
							$content .= '<td>Er dette privat?</td>';
							$content .= '<td>';
								$content .= '<select class="chosen-select edit-checklist-add-private" style="width:20%;" name="private">';
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
							$content .= '<select class="chosen-select edit-checklist-add-secondsOffset" style="width:20%;" name="secondsOffset">';
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
							$content .= '<input type="time" name="time" class="edit-checklist-add-time" placeholder="00:00" value="' . date('H:i') . '">';
						$content .= '</td>';
					$content .= '</tr>';
					$content .= '<div class="edit-checklist-add-nonPrivate">';

						if ($user->isGroupLeader() ||
							$user->isGroupCoLeader()) {
							$content .= '<tr>';
								$content .= '<td>Deleger til lag-leder</td>';
								$content .= '<td>';
									$content .= '<select class="chosen-select edit-checklist-add-teamId" style="width:20%;" name="teamId">';
										$content .= '<option value="0">Ingen</option>';

										foreach ($group->getTeams() as $team) {
											$content .= '<option value="' . $team->getId() . '">' . $team->getTitle() . '</option>';
										}

									$content .= '</select>';
								$content .= '</td>';
							$content .= '</tr>';
						}

						if ($user->isGroupLeader() ||
							$user->isGroupCoLeader() ||
							($user->isTeamMember() && $user->isTeamLeader())) {
							$content .= '<tr>';
								$content .= '<td>Deleger til medlem</td>';
								$content .= '<td>';
									$content .= '<select class="chosen-select edit-checklist-add-userId" name="userId">';
										$content .= '<option value="0">Ingen</option>';

										$memberList = $user->hasPermission('*') ? UserHandler::getMemberUsers() : $group->getMembers();

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

function getNoteList(array $noteList, $private) {
	$content = null;

	if (Session::isAuthenticated()) {
		$user = Session::getCurrentUser();

		if ($user->isGroupMember()) {
			$group = $user->getGroup();

			$content .= '<table>';
				$content .= '<tr>';
					if (!$private) {
						$content .= '<th>Eier</th>';
					}

					$content .= '<th>Oppgave</th>';
					$content .= '<th>Detaljer</th>';
					$content .= '<th>Dag</th>';
					$content .= '<th>Tidspunkt</th>';

					if (!$private) {
						if ($user->hasPermission('*') ||
							$user->isGroupLeader() ||
							$user->isGroupCoLeader()) {
							$content .= '<th>' . ($user->hasPermission('*') ? 'Crew/Lag' : 'Lag') . '</th>';
						}

						$content .= '<th>Delegert til</th>';
						$content .= '<th>Tilskuere</th>';
					}

				$content .= '</tr>';

				foreach ($noteList as $note) {
					$content .= '<tr>';
						$content .= '<form class="edit-checklist-edit" method="post">';
							$content .= '<input type="hidden" name="id" value="' . $note->getId() . '">';

							if (!$private) {
								if ($note->isWatching($user)) {
									$content .= '<td>Tilskuer</td>';
								} else {
									if ($note->isDelegated()) {
										if ($note->isOwner($user)) {
											$content .= '<td>Delegert til ' . $note->getUser()->getFirstname() . '</td>';
										} else if ($note->isUser($user)) {
											$content .= '<td>Delegert til deg</td>';
										} else {
											$content .= '<td>Delegert</td>';
										}
									} else {
										$content .= '<td>Din</td>';
									}
								}
							}

							$content .= '<td><input type="text" name="title" value="' . $note->getTitle() . '" placeholder="Skriv inn et gjøremål her..." required></td>';
							$content .= '<td><textarea name="content" rows="1" cols="20" placeholder="Skriv detaljer rundt gjøremålet her..." required>' . $note->getContent() . '</textarea></td>';
							$content .= '<td>';
								$content .= '<select class="chosen-select edit-checklist-edit-secondsOffset" style="width:100px;" name="secondsOffset">';
									$secondsOffset = $note->getSecondsOffset();

									// Søndag.
									$content .= '<option value="172800"' . ($secondsOffset <= 172800 && $secondsOffset > 86400 ? ' selected' : null) . '>Søndag</option>';

									// Lørdag.
									$content .= '<option value="86400"' . ($secondsOffset <= 86400 && $secondsOffset > 0 ? ' selected' : null) . '>Lørdag</option>';

									// Fredag.
									$content .= '<option value="0"' . ($secondsOffset <= 0 && $secondsOffset > -86400 ? ' selected' : null) . '>Fredag</option>';

									// Torsdag.
									$content .= '<option value="-86400"' . ($secondsOffset <= -86400 && $secondsOffset > -172800 ? ' selected' : null) . '>Torsdag</option>';

									// Adding weeks.
									for ($week = 1; $week <= 8; $week++) {
										$seconds = -$week * 604800;

										$content .= '<option value="' . $seconds . '"' . ($secondsOffset <= $seconds && $secondsOffset > ($seconds - 604800) ? ' selected' : null) . '>' . $week . ' ' . ($week > 1 ? 'uker' : 'uke') . ' før</option>';
									}

								$content .= '</select>';
							$content .= '</td>';
							$content .= '<td>';
								$content .= '<input type="time" name="time" class="edit-checklist-edit-time" placeholder="00:00" value="' . date('H:i', $note->getTime()) . '">';
							$content .= '</td>';

							if (!$private) {
								if ($user->hasPermission('*') ||
									$user->isGroupLeader() ||
									$user->isGroupCoLeader()) {
									$content .= '<td>';

										if ($user->hasPermission('*')) {
											$content .= '<select class="chosen-select" name="groupId">';

												foreach (GroupHandler::getGroups() as $group) {
													$content .= '<option value="' . $group->getId() . '"' . ($note->hasGroup() && $group->equals($note->getGroup()) ? ' selected' : null) . '>' . $group->getTitle() . '</option>';
												}

											$content .= '</select><br>';
										}

										if ($note->hasGroup()) {
											$content .= '<select class="chosen-select" name="teamId">';
												$content .= '<option value="0">Ingen</option>';

												foreach ($note->getGroup()->getTeams() as $team) {
													$content .= '<option value="' . $team->getId() . '"' . ($note->hasTeam() && $team->equals($note->getTeam()) ? ' selected' : null) . '>' . $team->getTitle() . '</option>';
												}

											$content .= '</select>';
										}

									$content .= '</td>';
								}


								if ($note->isOwner($user)) {
									$content .= '<td>';
										$content .= '<select class="chosen-select" name="userId">';
											$content .= '<option value="0">Ingen</option>';

											if ($user->hasPermission('*')) {
												$memberList = UserHandler::getMemberUsers();
											} else if ($user->isGroupLeader()) {
												$memberList = $group->getMembers();
											} else if ($user->isTeamMember() && $user->isTeamLeader()) {
												$memberList = $user->getTeam()->getMembers();
											}

											foreach ($memberList as $member) {
												if (!$member->equals($user)) {
													$content .= '<option value="' . $member->getId() . '"' . ($note->hasUser() && $member->equals($note->getUser()) ? ' selected' : null) . '>' . $member->getDisplayName() . '</option>';
												}
											}

										$content .= '</select>';
									$content .= '</td>';
									$content .= '<td>';
										$content .= '<select multiple class="chosen-select" name="watchingUserIdList[]" data-placeholder="Velg brukere...">';
											$watchingUserList = NoteHandler::getWatchingUsers($note);

											foreach (UserHandler::getMemberUsers() as $member) {
												$content .= '<option value="' . $member->getId() . '"' . (in_array($member, $watchingUserList) ? ' selected' : null) . '>' . $member->getDisplayName() . '</option>';
											}

										$content .= '</select>';
									$content .= '</td>';
								} else {
									$content .= '<td></td>';
									$content .= '<td></td>';
								}
							}

							$content .= '<td><input type="submit" value="Endre"></td>';

							if ($user->hasPermission('*') ||
								$note->isOwner($user)) {
								$content .= '<td><input type="button" value="Fjern" onClick="removeNote(' . $note->getId() . ')"></td>';
							}
						$content .= '</form>';
					$content .= '</tr>';
				}

			$content .= '</table>';
		}
	}

	return $content;
}
?>
